from flask import Blueprint, request, jsonify

from flask_app.config import Config
from flask_app.app.services.migration import Migration
from flask_app.app.services.document_service import DocumentService
from flask_app.app.databases.milvus_connection import MilvusConnection
from flask_app.app.services.plagiarism_checker_service import PlagiarismCheckerService

# Create a Blueprint for main routes
app = Blueprint("main", __name__, url_prefix="/v1/api")


@app.route("/ping")
def pong():
    return "pong"


@app.route("/migrate")
def migrate():
    success = Migration.up()

    if success:
      return jsonify({"success": True, "message": "Migrate data is successfully"})
    else:
      return jsonify({"success": False, "message": "Migrate data is failed"})


@app.route("/rollback")
def rollback():
    success = Migration.down()

    if success:
      return jsonify({"success": True, "message": "Rollback data is successfully"})
    else:
      return jsonify({"success": False, "message": "Rollback data is failed"})


@app.route("/show-db")
def show():
    res = MilvusConnection.get_client().describe_collection(
        collection_name="documents"
    )
    return jsonify(res)


@app.route("/data/upload", methods=["POST"])
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


@app.route("/plagiarism-checker", methods=["POST"])
def check_document_plagiarism():
    if "files" not in request.files:
        return jsonify({"error": "No file uploaded"}), 400

    try:
        plagiarism_checker = PlagiarismCheckerService(Config.MINILM_EMBEDDING_MODEL)
        results = plagiarism_checker.check_plagiarism(request.files["files"])
    
        return jsonify({
            "success": True,
            "data": {
                "matches": results,
                "total": len(results)
            }
        })
    except Exception as e:
        return jsonify({"error": str(e)}), 500
