import numpy as np
from flask import jsonify
from werkzeug.datastructures import FileStorage

from flask_app.app.services.file_handler import FileHandler
from flask_app.app.databases.milvus_connection import MilvusConnection
from flask_app.app.services.process_text_service import ProcessTextService
from flask_app.app.factories.embedding_model_factory import EmbeddingModelFactory

class PlagiarismCheckerService:
    def __init__(self, embedding_model: str):
        self.file_handler = FileHandler()
        self.text_service = ProcessTextService()
        self.client = MilvusConnection.get_client()
        self.embedding_service = EmbeddingModelFactory.get_model(embedding_model)

    def check_plagiarism(self, file: FileStorage) -> jsonify:
        print("👉 Starting check plagiarism")
        
        file_path = self.file_handler.save_file(file)
        text = self.file_handler.extract_text_from_file(file_path)
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
            output_fields=["document_id", "subject_code"],
            **search_params,
            filter="subject_code like \"CNTT%\""
        )

        search_results = []
        if isinstance(res, list) and len(res) > 0:
            for hits in res:
                for hit in hits:
                    search_results.append({
                        'document_id': str(hit.get('document_id', '')),  # Access directly with get()
                        'subject_code': hit.get('subject_code', 'CNTT'),
                        'similarity_score': float(hit['distance']) if 'distance' in hit else 0.0
                    })

        print("✅ Check plagiarism successfully")
        return search_results 
