import uuid
import numpy as np

from werkzeug.datastructures import FileStorage
from flask_app.app.services.file_handler import FileHandler
from flask_app.app.services.database_handler import DatabaseHandler
from flask_app.app.services.process_text_service import ProcessTextService
from flask_app.app.factories.embedding_model_factory import EmbeddingModelFactory
from flask_app.app.databases.milvus_connection import MilvusConnection

class DocumentService:
    def __init__(self, embedding_model: str):
        self.file_handler = FileHandler()
        self.db_handler = DatabaseHandler()
        self.text_service = ProcessTextService()
        self.client = MilvusConnection.get_client()
        self.embedding_service = EmbeddingModelFactory.get_model(embedding_model)

    def upload_document(self, file: FileStorage = None, metadata: dict = None) -> bool:
        try:
            print("🎯 Upload paragraphs to database")

            file_path = self.file_handler.save_file(file)
            raw_text = self.file_handler.extract_text_from_file(file_path)
            document_word_count = self.text_service.clean_text(raw_text, True)
            self.file_handler.remove_file(file_path)

            # Extract metadata from request body
            document_id = int(metadata.get('document_id')) if metadata else None
            subject_code = metadata.get('subject_code')
            original_name = metadata.get('original_name')

            # ✅ 1. Chunk into smaller (sentences or paragraphs)
            chunk_texts = self.text_service.chunk_text(raw_text)

            # ✅ 2. Preprocess and convert chunked text to embedding
            documents_to_insert = []
            for index, chunk_text in enumerate(chunk_texts):
                origin_text, clean_text = self.text_service.preprocess_text(chunk_text)
                
                if len(clean_text.strip()) < 50:
                    continue  # skip very short paragraphs

                embedding = self.embedding_service.convert_text_to_embedding(clean_text)
                if isinstance(embedding, np.ndarray):
                    embedding = embedding.tolist()

                doc = {
                    "sentence_id": uuid.uuid4().int % (2**63),
                    "document_id": document_id,
                    "subject_code": subject_code,
                    "original_name": original_name,
                    "embedding": embedding,
                    "raw_text": chunk_text,
                    "sentence_word_count": len(clean_text.split()),
                    "document_word_count": document_word_count,
                }
                documents_to_insert.append(doc)

            if not documents_to_insert:
                print("❌ No valid paragraphs to insert")
                return False
            
            count = self.db_handler.insert_documents(documents_to_insert)
            return count > 0

        except Exception as e:
            print(f"❌ Error uploading document: {str(e)}")
            return False
