import numpy as np
from pymilvus import Collection
from flask import current_app, jsonify
from werkzeug.datastructures import FileStorage

from flask_app.config import Config
from flask_app.app.services.file_handler import FileHandler
from flask_app.app.services.process_text_service import ProcessTextService
from flask_app.app.factories.embedding_model_factory import EmbeddingModelFactory

class PlagiarismCheckerService:
    def __init__(self, embedding_model: str):
        self.milvus_database = current_app.milvus_connection.get_connection()
        self.collection = Collection(Config.DOCUMENT_COLLECTION_NAME)
        self.collection.load()
        self.file_handler = FileHandler()
        self.text_service = ProcessTextService()
        self.embedding_service = EmbeddingModelFactory.get_model(embedding_model)

    def check_plagiarism(self, file: FileStorage) -> jsonify:
        print("👉 Starting check plagiarism")
        
        file_path = self.file_handler.save_file(file)
        text = self.file_handler.extract_text_from_file(file_path)
        processed_text = self.text_service.preprocess_text(text)
        embedding = self.embedding_service.convert_text_to_embedding(processed_text[:1000])

        if isinstance(embedding, np.ndarray):
            embedding = embedding.tolist()

        # Query the embedding in database
          # # Perform similarity search in Milvus
          # search_params = {"metric_type": "L2", "params": {"nprobe": 10}}
          # results = collection.search([embedding], "embedding", search_params, limit=5, output_fields=["source_text"])
        
        # Calculate duplicate text metrics
        
        # Highlight the duplicated text
          # matches = []
          # for hit in results[0]:
          #     matches.append({
          #         "source_text": hit.entity.get("source_text"),
          #         "similarity": 1 - hit.distance,  # Convert distance to similarity
          #         "highlighted_text": processed_text  # Placeholder for highlighted text
          #     })

        print("✅ Check plagiarism successfully")
        return jsonify({"embedding": embedding})