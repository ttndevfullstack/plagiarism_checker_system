import os
import base64

from flask_app.config import Config
from flask import Blueprint, request, jsonify
from flask_app.app.services.migration import Migration
from flask_app.app.services.nltk_service import NLTKService
from flask_app.app.services.pdf_processor import PDFProcessor
from flask_app.app.services.document_service import DocumentService
from flask_app.app.services.plagiarism_checker_service import PlagiarismCheckerService
from flask_app.app.services.database_handler import DatabaseHandler

# Create a Blueprint for main routes
bp_v1 = Blueprint("main", __name__, url_prefix="/v1/api")


@bp_v1.route("/health")
def health():
    return "OK", 200


@bp_v1.route("/migrate")
def migrate():
    success = Migration.up()

    if success:
      return jsonify({"success": True, "message": "Migrate data is successfully"})
    else:
      return jsonify({"success": False, "message": "Migrate data is failed"})


@bp_v1.route("/rollback")
def rollback():
    success = Migration.down()

    if success:
      return jsonify({"success": True, "message": "Rollback data is successfully"})
    else:
      return jsonify({"success": False, "message": "Rollback data is failed"})


@bp_v1.route("/data/upload", methods=["POST"])
def upload_data():
    file = request.files.get('files')
    if not file:
        return jsonify({
            "success": False, 
            "error": f"No file uploaded. Received files: {list(request.files.keys())}"
        }), 400

    try:
        metadata = request.form.to_dict()
        document_service = DocumentService(Config.ACTIVE_EMBEDDING_MODEL)
        success = document_service.upload_document(file, metadata)

        if success:
            return jsonify({"success": True, "message": "Successfully uploaded document", "data": success}), 201
        
        return jsonify({"success": False, "error": "Document insertion failed"}), 500

    except Exception as e:
        print(f"Error processing upload: {str(e)}")
        return jsonify({"success": False, "error": f"Internal Server Error: {str(e)}"}), 500
    

@bp_v1.route("/documents", methods=["GET"])
def list_documents():
    try:
        db_handler = DatabaseHandler()
        docs = db_handler.get_all_documents_basic_info()
        return jsonify({
            "success": True,
            "data": docs
        }), 200
    except Exception as e:
        return jsonify({
            "success": False,
            "error": f"Failed to fetch documents: {str(e)}"
        }), 500


@bp_v1.route("/documents/delete/<int:document_id>", methods=["DELETE"])
def delete_documents(document_id):
    try:
        db_handler = DatabaseHandler()
        delete_count = db_handler.delete_documents_by_document_id(document_id)
        return jsonify({
            "success": True,
            "message": f"Deleted {delete_count} records with document_id={document_id}."
        }), 200
    except Exception as e:
        return jsonify({
            "success": False,
            "error": f"Failed to delete records: {str(e)}"
        }), 500


@bp_v1.route("/plagiarism-checker/text", methods=["POST"])
def check_plagiarism_by_text():
    if request.is_json:
        data = request.get_json()
        if not data:
            return jsonify({"error": "No JSON data provided"}), 400
            
        if "content" in data:
            content = data["content"]
            
            plagiarism_checker = PlagiarismCheckerService(Config.ACTIVE_EMBEDDING_MODEL)
            results = plagiarism_checker.check_plagiarism(content)
            return jsonify(results)
        else:
            return jsonify({"error": "Missing 'content' field in JSON"}), 400
    else:
        return jsonify({"error": "No file or content provided"}), 400


@bp_v1.route("/plagiarism-checker/file", methods=["POST"])
def check_plagiarism_by_file():
    if 'file' not in request.files:
        return jsonify({"error": "No file provided"}), 400
        
    file = request.files['file']
    if (
        file.filename == '' or
        (not file.filename.lower().endswith('.pdf') and not file.filename.lower().endswith('.docx'))
    ):
        return jsonify({"error": "Invalid file format. Please upload a PDF or DOCX file"}), 400

    try:
        pdf_processor = PDFProcessor(Config.ACTIVE_EMBEDDING_MODEL)
        highlighted_pdf_path, results = pdf_processor.process_pdf(file)
        
        # Read the PDF file and encode it
        with open(highlighted_pdf_path, 'rb') as pdf_file:
            pdf_content = pdf_file.read()
            pdf_base64 = base64.b64encode(pdf_content).decode('utf-8')
        
        # Clean up the temporary file
        os.remove(highlighted_pdf_path)
        
        response = {
            "success": True,
            "message": "Plagiarism check completed successfully",
            "data": {
                "results": results,
                "pdf_content": pdf_base64
            }
        }
        
        return jsonify(response), 200
        
    except Exception as e:
        return jsonify({"error": f"Processing failed: {str(e)}"}), 500
