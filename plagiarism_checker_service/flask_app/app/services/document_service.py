import uuid
import numpy as np
from werkzeug.datastructures import FileStorage

from flask_app.config import Config
from flask_app.app.services.file_handler import FileHandler
from flask_app.app.services.pdf_processor import PDFProcessor
from flask_app.app.services.database_handler import DatabaseHandler
from flask_app.app.services.process_text_service import ProcessTextService
from flask_app.app.factories.embedding_model_factory import EmbeddingModelFactory
from flask_app.app.databases.milvus_connection import MilvusConnection

class DocumentService:
    def __init__(self, embedding_model: str):
        self.file_handler = FileHandler()
        self.pdf_processor = PDFProcessor('MiniLM')
        self.db_handler = DatabaseHandler()
        self.text_service = ProcessTextService()
        self.client = MilvusConnection.get_client()
        self.embedding_service = EmbeddingModelFactory.get_model(embedding_model)

    def upload_document(self, file: FileStorage = None, metadata: dict = None) -> bool:
        try:
            print("🎯 Upload paragraphs to database")

            # ✅ 1. Save file and count document words
            file_path = self.file_handler.save_file(file)

            # ✅ 2. Extract metadata from request body
            document_id = int(metadata.get('document_id')) if metadata else None
            subject_code = metadata.get('subject_code')
            original_name = metadata.get('original_name')

            # ✅ 3. Chunk into smaller (sentences or paragraphs)
            sentences, document_word_count = self.pdf_processor._extract_sentences(file_path)
            chunked_texts = [s["combined_text"] for s in sentences.values()]

            # ✅ 4. Preprocess, convert chunked text to embedding and save to database
            documents_to_insert = []
            for index, chunk_text in enumerate(chunked_texts):
                clean_text, processed_text = self.text_service.preprocess_text(chunk_text)
                
                if len(clean_text.strip()) < getattr(Config, "MIN_CHUNKED_TEXT_LENGTH", 15):
                    continue  # skip very short paragraphs

                embedding = self.embedding_service.convert_text_to_embedding(processed_text)
                if isinstance(embedding, np.ndarray):
                    embedding = embedding.tolist()

                doc = {
                    "sentence_id": uuid.uuid4().int % (2**63),
                    "document_id": document_id,
                    "subject_code": subject_code,
                    "original_name": original_name,
                    "embedding": embedding,
                    "raw_text": chunk_text.replace('\n', ' '),
                    "sentence_word_count": len(processed_text.split()),
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
        finally:
            self.file_handler.remove_file(file_path)
