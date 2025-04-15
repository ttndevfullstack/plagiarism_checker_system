from pymilvus import db, MilvusClient, Collection, CollectionSchema, FieldSchema, DataType
from pymilvus.exceptions import MilvusException
from flask_app.config import Config
from flask_app.app.helpers import Helper
import logging

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

    def create_database(self):
        """Create a database if it doesn't exist."""
        databases = db.list_database(using=Config.MILVUS_ALIAS)
        
        if Config.MILVUS_DB_NAME not in databases:
            db.create_database(Config.MILVUS_DB_NAME, using=Config.MILVUS_ALIAS)
            print(f"✅ Created database: {Config.MILVUS_DB_NAME}")
        else:
            print(f"✅ Database {Config.MILVUS_DB_NAME} already exists")

        # Use the database
        db.using_database(Config.MILVUS_DB_NAME, using=Config.MILVUS_ALIAS)

    def create_collection(self):
        """Create collection if it doesn't exist"""
        try:
            collection_name = Config.DOCUMENT_COLLECTION_NAME
            if not self.client.has_collection(collection_name):
                fields = [
                    FieldSchema(name="id", dtype=DataType.INT64, is_primary=True, auto_id=True),
                    FieldSchema(name="document_id", dtype=DataType.INT64),
                    FieldSchema(name="subject_code", dtype=DataType.VARCHAR, max_length=100),
                    FieldSchema(name="text", dtype=DataType.VARCHAR, max_length=65535),
                    FieldSchema(name="embedding", dtype=DataType.FLOAT_VECTOR, dim=384)
                ]
                schema = CollectionSchema(fields=fields, description="Document collection")
                collection = Collection(name=collection_name, schema=schema, using=Config.MILVUS_ALIAS)
                
                index_params = {
                    "metric_type":"L2",
                    "index_type":"IVF_FLAT",
                    "params":{"nlist":1024}
                }
                collection.create_index(field_name="embedding", index_params=index_params)
                print(f"✅ Created collection {collection_name}")
            else:
                print(f"✅ Collection {collection_name} already exists")
        except Exception as e:
            print(f"❌ Error creating collection: {str(e)}")
            raise
