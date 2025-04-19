import os
import numpy as np
import uuid
from werkzeug.datastructures import FileStorage

from flask_app.app.services.file_handler import FileHandler
from flask_app.app.services.process_text_service import ProcessTextService
from flask_app.app.factories.embedding_model_factory import EmbeddingModelFactory
from flask_app.app.databases.milvus_connection import MilvusConnection

class DatabaseHandler:
    def __init__(self):
        pass

    def insert_document(self, document) -> int:
        """Insert embeddings into Milvus"""
        try:
            result = MilvusConnection.get_client().insert(
                collection_name="documents",
                # partition_name=document.subject_code,
                data=[document]
            )

            print(f"   ✅ Inserted document into database successfully")
            return result['insert_count']
        except Exception as e:
            print(f"   ❌ Error inserting document into database: {str(e)}")
            raise
 
    def upsert_document(self, document) -> int:
        """Insert embeddings into Milvus"""
        try:
            result = MilvusConnection.get_client().upsert(
                collection_name="documents",
                # partition_name=document.subject_code,
                data=[document]
            )

            print(f"   ✅ Upserted document into database successfully")
            # Milvus returns 'upsert_count' for upsert operations
            return result.get('upsert_count', 0)
        except Exception as e:
            print(f"   ❌ Error upserting document into database: {str(e)}")
            raise