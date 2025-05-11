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
            self.file_handler.remove_file(file_path)

            # ✅ 1. Chunk into paragraphs
            paragraphs = self.text_service.chunk_text_into_paragraphs(raw_text)

            document_id = int(metadata.get('document_id')) if metadata else None
            subject_code = metadata.get('subject_code')
            original_name = metadata.get('original_name')

            # ✅ 2. Prepare per-paragraph embeddings
            documents_to_insert = []
            for i, paragraph in enumerate(paragraphs):
                clean_text = self.text_service.preprocess_text(paragraph)
                if len(clean_text.strip()) < 50:
                    continue  # skip very short paragraphs

                embedding = self.embedding_service.convert_text_to_embedding(clean_text)
                if isinstance(embedding, np.ndarray):
                    embedding = embedding / np.linalg.norm(embedding)  # normalize
                    embedding = embedding.tolist()

                doc = {
                    "document_id": document_id,
                    "paragraph_id": i,
                    "subject_code": subject_code,
                    "original_name": original_name,
                    "embedding": embedding
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
