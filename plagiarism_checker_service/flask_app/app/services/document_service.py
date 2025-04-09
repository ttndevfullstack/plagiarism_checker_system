import numpy as np
from werkzeug.datastructures import FileStorage
from pymilvus import Collection

from flask_app.config import Config
from flask_app.app.services.file_handler import FileHandler
from flask_app.app.services.process_text_service import ProcessTextService
from flask_app.app.factories.embedding_model_factory import EmbeddingModelFactory
from flask_app.app.databases.milvus_connection import MilvusConnection

class DocumentService:
    def __init__(self, embedding_model: str):
        self.milvus_connection = MilvusConnection()
        self.client = self.milvus_connection.get_connection()
        
        try:
            self.collection = Collection(
                name=Config.DOCUMENT_COLLECTION_NAME,
                schema=None,
                using=Config.MILVUS_ALIAS
            )
            self.collection.load()
        except Exception as e:
            print(f"Error loading collection: {str(e)}")
            raise
            
        self.file_handler = FileHandler()
        self.text_service = ProcessTextService()
        self.embedding_service = EmbeddingModelFactory.get_model(embedding_model)

    def upload_document(self, file: FileStorage, metadata: dict = None) -> bool:
        try:
            file_path = self.file_handler.save_file(file)
            # text = self.file_handler.extract_text_from_file(file_path)
            # processed_text = self.text_service.preprocess_text(text)
            # embedding = self.embedding_service.convert_text_to_embedding(processed_text[:1000])
            # if isinstance(embedding, np.ndarray):
            #     embedding = embedding.tolist()()

            # document_id = metadata.get('document_id') if metadata else None
            # subject_code = metadata.get('subject_code') if metadata else None

            # insert_count = self.insert_to_db(
            #     embedding=embedding,
            #     document_id=document_id,
            #     source_text=processed_text,
            #     subject_code=subject_code,
            #     metadata=metadata
            # )
            return True

            print("✅ Uploaded documents successfully")
            return insert_count > 0

        except Exception as e:
            print(f"❌ Error uploading document: {str(e)}")
            return False

    def insert_to_db(self, embedding, source_text, subject_code=None, category=None, metadata=None) -> int:
        """Insert embeddings into Milvus"""
        try:
            data = [
                [source_text],
                [subject_code if subject_code else ""],
                [embedding]
            ]
            
            mr = self.collection.insert(data)
            self.collection.flush()
            print(f"   ✅ Inserted document into database successfully")
            return len(mr.primary_keys)
        except Exception as e:
            print(f"   ❌ Error inserting document into database: {str(e)}")
            raise