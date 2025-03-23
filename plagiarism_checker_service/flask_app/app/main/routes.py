from pymilvus import db
from flask import Blueprint, request, jsonify, current_app
from pymilvus import db

from flask_app.config import Config
from flask_app.app.services.document_service import DocumentService
from flask_app.app.services.plagiarism_checker_service import PlagiarismCheckerService

# Create a Blueprint for main routes
bp_v1 = Blueprint("main", __name__, url_prefix="/v1")


@bp_v1.route("/ping")
def pong():
    return "pong"


@bp_v1.route("/show-db")
def show():
    databases = db.list_database(using=current_app.milvus_connection.get_connection())
    return jsonify(databases)


@bp_v1.route("/data/upload", methods=["POST"])
def upload_data():
    if "files" not in request.files:
        return jsonify({"success": False, "error": "No file uploaded"}), 400

    try:
        document_service = DocumentService(Config.MINILM_EMBEDDING_MODEL)
        success = document_service.upload_document(request.files["files"])

        if success:
            return jsonify({"success": True, "message": "Successfully uploaded document"}), 201
        else:
            return jsonify({"success": False, "error": "Document insertion failed"}), 500

    except Exception as e:
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
