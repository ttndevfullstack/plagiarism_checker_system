import os
from flask import Flask
from flask_cors import CORS
from flask_app.config import Config

def create_app():
    app = Flask(__name__)
    CORS(app)
    app.config.from_object(Config)

    # Make dir to save uploaded files
    os.makedirs(Config.FILE_STORAGE_DIR, exist_ok=True)

    # Register blueprints
    from .routes_v1 import app
    app.register_blueprint(app)

    return app
