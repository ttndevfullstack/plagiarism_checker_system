from flask import Blueprint, request, jsonify

from flask_app.config import Config
from flask_app.app.services.migration import Migration
from flask_app.app.services.document_service import DocumentService
from flask_app.app.databases.milvus_connection import MilvusConnection
from flask_app.app.services.plagiarism_checker_service import PlagiarismCheckerService

# Create a Blueprint for main routes
bp_v1 = Blueprint("main", __name__, url_prefix="/v1/api")


@bp_v1.route("/ping")
def pong():
    return "pong"


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


@bp_v1.route("/show-db")
def show():
    res = MilvusConnection.get_client().describe_collection(
        collection_name="documents"
    )
    return jsonify(res)


@bp_v1.route("/data/documents/clear", methods=["GET"])
def clear_data():
    try:
        client = MilvusConnection.get_client()
        client.delete(
            collection_name="documents",
            filter="document_id > 0"
        )

        return jsonify({
            "success": True,
            "message": "Successfully cleared all documents from the collection"
        })
    except Exception as e:
        return jsonify({
            "success": False,
            "error": f"Failed to clear collection: {str(e)}"
        }), 500


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
        
        document_service = DocumentService(Config.MINILM_EMBEDDING_MODEL)
        success = document_service.upload_document(file, metadata)
        
        if success:
            return jsonify({"success": True, "message": "Successfully uploaded document"}), 201
        
        return jsonify({"success": False, "error": "Document insertion failed"}), 500

    except Exception as e:
        print(f"Error processing upload: {str(e)}")
        return jsonify({"success": False, "error": f"Internal Server Error: {str(e)}"}), 500


@bp_v1.route("/plagiarism-checker", methods=["POST"])
def check_document_plagiarism():
    if "file" in request.files:
        file = request.files["file"]
        try:
            plagiarism_checker = PlagiarismCheckerService(Config.MINILM_EMBEDDING_MODEL)
            results = plagiarism_checker.check_plagiarism(file)
            return jsonify(results)
        except Exception as e:
            return jsonify({"error": str(e)}), 500
    elif "content" in request.form:
        try:
            content = request.form["content"]
            plagiarism_checker = PlagiarismCheckerService(Config.MINILM_EMBEDDING_MODEL)
            results = plagiarism_checker.check_plagiarism_content(content)
            return jsonify(results)
        except Exception as e:
            return jsonify({"error": str(e)}), 500
    else:
        return jsonify({"error": "No file or content provided"}), 400
