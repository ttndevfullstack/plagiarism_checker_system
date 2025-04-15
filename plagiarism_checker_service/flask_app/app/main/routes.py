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
    success = Migration.run()

    if success:
      return jsonify({"success": True, "message": "Migrate data is successfully"})
    else:
      return jsonify({"success": False, "message": "Migrate data is failed"})


@bp_v1.route("/show-db")
def show():
    res = MilvusConnection.get_client().describe_collection(
        collection_name="documents"
    )
    return jsonify(res)


@bp_v1.route("/data/upload/test")
def test_upload_data():
    document_service = DocumentService(Config.MINILM_EMBEDDING_MODEL)
    result = document_service.upload_document("Kubernetes services, support, and tools are widely available")
    return jsonify({"success": True, "data": result})


@bp_v1.route("/data/check/test", methods=["POST"])
def test_search_data():
    try:
        data = request.get_json()
        if not data or 'text' not in data:
            return jsonify({
                "success": False,
                "error": "No text provided in request body",
                "data": {
                    "matches": [],
                    "total": 0
                }
            }), 400

        document_service = DocumentService(Config.MINILM_EMBEDDING_MODEL)
        search_results = document_service.search_documents(data['text'])
        
        if search_results is False:
            return jsonify({
                "success": False,
                "error": "Failed to search documents",
                "data": {
                    "matches": [],
                    "total": 0
                }
            }), 500
            
        return jsonify({
            "success": True,
            "data": {
                "matches": search_results,
                "total": len(search_results)
            }
        })
        
    except Exception as e:
        return jsonify({
            "success": False,
            "error": str(e),
            "data": {
                "matches": [],
                "total": 0
            }
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
    if "files" not in request.files:
        return jsonify({"error": "No file uploaded"}), 400

    try:
        plagiarism_checker = PlagiarismCheckerService(Config.MINILM_EMBEDDING_MODEL)
        result = plagiarism_checker.check_plagiarism(request.files["files"])
        return result
    except Exception as e:
        return jsonify({"error": str(e)}), 500
