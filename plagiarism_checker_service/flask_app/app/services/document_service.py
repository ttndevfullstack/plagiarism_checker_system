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
            print("🎯 Upload documents to database")

            file_path = self.file_handler.save_file(file)
            text = self.file_handler.extract_text_from_file(file_path)
            processed_text = self.text_service.preprocess_text(text)
            embedding = self.embedding_service.convert_text_to_embedding(processed_text)
            # self.file_handler.remove_file(file_path)
            
            if isinstance(embedding, np.ndarray):
                embedding = embedding.tolist()

            document_id = metadata.get('document_id') if metadata else None
            subject_code = metadata.get('subject_code') if metadata else None
            original_name = metadata.get('original_name') if metadata else None

            document = {
                "document_id": int(document_id),
                "subject_code": subject_code,
                "original_name": original_name,
                "embedding": embedding,
            }

            is_valid = self.validate_document_data(document)
            if not is_valid:
                print("❌ Document is not valid")
                return False

            try:
                insert_count = self.db_handler.upsert_document(document)
                print("🎯 Uploaded documents successfully")
                return True if insert_count > 0 else False
            except Exception as db_error:
                print(f"❌ Database error: {str(db_error)}")
                return False

        except Exception as e:
            print(f"❌ Error uploading document: {str(e)}")
            return False
        
    def validate_document_data(self, document) -> bool:
      try:
        required_fields = ['document_id', 'subject_code', 'embedding']
        
        # Check if all required fields exist
        if not all(field in document for field in required_fields):
          print("❌ Missing required fields in document data")
          return False
          
        # Check if any field has None or empty value
        if any(not document[field] for field in required_fields):
          print("❌ Required fields cannot be empty")
          return False
          
        return True
        
      except Exception as e:
        print(f"❌ Error validating document data: {str(e)}")
        return False
      
    def search_documents(self, text):
        try:
            processed_text = self.text_service.preprocess_text(text)
            search_embedding = self.embedding_service.convert_text_to_embedding(processed_text)
            
            if isinstance(search_embedding, np.ndarray):
                search_embedding = search_embedding.tolist()

            search_params = {
                "metric_type": "COSINE",
                "offset": 0,
                "limit": 5,
                "params": {"nprobe": 16}
            }

            res = self.client.search(
                collection_name="documents",
                data=[search_embedding],
                anns_field="embedding",
                output_fields=["document_id", "source_text", "subject_code"],
                **search_params,
                filter="subject_code like \"CNTT%\""
            )

            search_results = []
            if isinstance(res, list) and len(res) > 0:
                for hits in res:
                    for hit in hits:
                        search_results.append({
                            'document_id': str(hit.get('document_id', '')),  # Access directly with get()
                            'source_text': hit.get('source_text', ''),
                            'subject_code': hit.get('subject_code', 'CNTT'),
                            'similarity_score': float(hit['distance']) if 'distance' in hit else 0.0
                        })

            return search_results 
            
        except Exception as e:
            print(f"❌ Error searching document data: {str(e)}")
            return False