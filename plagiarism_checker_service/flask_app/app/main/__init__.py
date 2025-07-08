import os
from flask import Flask
from flask_cors import CORS
from flask_app.config import Config
from flask_app.app.services.nltk_service import NLTKService
from flask_app.app.services.migration import Migration
from flask_app.app.databases.milvus_connection import MilvusConnection

def create_app():
    app = Flask(__name__)
    CORS(app)
    app.config.from_object(Config)

    # Ensure NLTK data is available
    NLTKService.ensure_nltk_data()

    # Make dir to save uploaded files
    os.makedirs(Config.FILE_STORAGE_DIR, exist_ok=True)

    # Initialize Milvus connection and create database if no exists
    MilvusConnection.create_database()

    # Migrate collections
    Migration.up()

    # Register blueprints
    from .routes_v1 import bp_v1
    app.register_blueprint(bp_v1)

    return app
