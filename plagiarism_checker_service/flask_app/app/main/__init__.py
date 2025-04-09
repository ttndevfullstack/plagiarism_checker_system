import os
from flask import Flask
from flask_cors import CORS

from flask_app.config import Config
from flask_app.app.databases.milvus_connection import MilvusConnection

def create_app():
    app = Flask(__name__)
    CORS(app)
    app.config.from_object(Config)

    # Make dir to save uploaded files
    os.makedirs(Config.FILE_STORAGE_DIR, exist_ok=True)

    # Register blueprints
    from .routes import bp_v1
    app.register_blueprint(bp_v1)

    return app
