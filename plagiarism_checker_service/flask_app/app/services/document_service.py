import numpy as np
from flask import current_app
from werkzeug.datastructures import FileStorage
from pymilvus import Collection

from flask_app.config import Config
from flask_app.app.services.file_handler import FileHandler
from flask_app.app.services.process_text_service import ProcessTextService
from flask_app.app.factories.embedding_model_factory import EmbeddingModelFactory


class DocumentService:
    def __init__(self, embedding_model: str):
        self.milvus_database = current_app.milvus_connection.get_connection()
        self.collection = Collection(Config.DOCUMENT_COLLECTION_NAME)
        self.collection.load()
        self.file_handler = FileHandler()
        self.text_service = ProcessTextService()
        self.embedding_service = EmbeddingModelFactory.get_model(embedding_model)

    def upload_document(self, file: FileStorage, metadata: dict = None) -> bool:
        print("👉 Starting upload documents")

        file_path = self.file_handler.save_file(file)
        text = self.file_handler.extract_text_from_file(file_path)
        processed_text = self.text_service.preprocess_text(text)
        embedding = self.embedding_service.convert_text_to_embedding(processed_text[:1000])

        if isinstance(embedding, np.ndarray):
            embedding = embedding.tolist()

        subject_code = metadata.get('subject_code') if metadata else None
        
        insert_count = self.insert_to_db(
            embedding, 
            processed_text,
            subject_code=subject_code,
        )

        print("✅ Uploaded documents successfully")
        return insert_count > 0

    def insert_to_db(self, embedding, source_text, subject_code=None, category=None, metadata=None) -> int:
        """Insert embeddings into Milvus"""
        try:
            data = [
                [source_text],
                [subject_code],
                [embedding]
            ]
            insert_result = self.collection.insert(data)

            print(f"   ✅ Inserted {len(insert_result.primary_keys)} document(s) into database successfully")
            return len(insert_result.primary_keys)

        except Exception as e:
            print(f"   ❌ Error inserting document into database: {str(e)}")
            return 0