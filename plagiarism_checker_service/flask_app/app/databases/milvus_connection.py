import logging
from flask_app.config import Config
from flask_app.app.helpers import Helper
from pymilvus import connections, db, MilvusClient
from pymilvus.exceptions import MilvusException

class MilvusConnection:
    _client = None
    
    @staticmethod
    def get_client() -> MilvusClient:
        """ Get MilvusClient from singleton instance """
        if MilvusConnection._client is None:
            try:
                protocol = "https" if Helper.isProduction() else "http"
                MilvusConnection._client = MilvusClient(
                    uri=f"{protocol}://{Config.MILVUS_DB_HOST}:{Config.MILVUS_DB_PORT}",
                    user=Config.MILVUS_DB_USERNAME,
                    password=Config.MILVUS_DB_PASSWORD,
                    db_name=Config.MILVUS_DB_NAME,
                    token=f"{Config.MILVUS_DB_USERNAME}:{Config.MILVUS_DB_PASSWORD}",
                )
            except MilvusException as e:
                logging.error(f"Failed to connect to Milvus: {e}")
                raise ConnectionError("Could not establish connection to Milvus.") from e
            except Exception as e:
                logging.exception("Unexpected error while connecting to Milvus")
                raise

        return MilvusConnection._client

    @staticmethod
    def initialize_connection():
        """Initialize connection to Milvus server"""
        try:
            protocol = "https" if Helper.isProduction() else "http"
            connections.connect(
                alias=Config.MILVUS_ALIAS,
                uri=f"{protocol}://{Config.MILVUS_DB_HOST}:{Config.MILVUS_DB_PORT}",
                user=Config.MILVUS_DB_USERNAME,
                password=Config.MILVUS_DB_PASSWORD,
            )
            print(f"✅ Connected to Milvus server at {Config.MILVUS_DB_HOST}:{Config.MILVUS_DB_PORT}")
        except Exception as e:
            logging.error(f"Failed to connect to Milvus: {e}")
            raise ConnectionError("Could not establish connection to Milvus.") from e

    @staticmethod
    def create_database():
        """Create a database if it doesn't exist."""
        MilvusConnection.initialize_connection()
        
        databases = db.list_database(using=Config.MILVUS_ALIAS)
        
        if Config.MILVUS_DB_NAME not in databases:
            db.create_database(Config.MILVUS_DB_NAME, using=Config.MILVUS_ALIAS)
            print(f"✅ Created database: {Config.MILVUS_DB_NAME}")
        else:
            print(f"✅ Database {Config.MILVUS_DB_NAME} already exists")

        # Use the database
        db.using_database(Config.MILVUS_DB_NAME, using=Config.MILVUS_ALIAS)