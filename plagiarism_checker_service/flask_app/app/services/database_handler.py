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

            print(f"   ✅ Inserted document into database successfully")
            return result['insert_count']
        except Exception as e:
            print(f"   ❌ Error upserting document into database: {str(e)}")
            raise

    # def search_documents(self, search_text: str, top_k: int = 5, subject_code: str = None) -> list:
    #     """
    #     Search for similar documents using vector similarity search
    #     Args:
    #         search_text (str): Text to search for
    #         top_k (int): Number of results to return
    #         subject_code (str): Filter by subject code
    #     Returns:
    #         list: List of search results with scores
    #     """
    #     try:
    #         # Process and embed search text
    #         processed_text = self.text_service.preprocess_text(search_text)
    #         search_embedding = self.embedding_service.convert_text_to_embedding(processed_text)
            
    #         if isinstance(search_embedding, np.ndarray):
    #             search_embedding = search_embedding.tolist()

    #         # Prepare search parameters
    #         search_params = {
    #             "metric_type": "L2",
    #             "params": {"nprobe": 10},
    #         }

    #         # Build expression for filtering if subject_code is provided
    #         expr = f'subject_code == "{subject_code}"' if subject_code else None

    #         # Execute search
    #         results = self.collection.search(
    #             data=[search_embedding],
    #             anns_field="embedding",
    #             param=search_params,
    #             limit=top_k,
    #             expr=expr,
    #             output_fields=["source_text", "subject_code"]
    #         )

    #         # Format results
    #         search_results = []
    #         for hits in results:
    #             for hit in hits:
    #                 search_results.append({
    #                     'score': hit.score,
    #                     'source_text': hit.entity.get('source_text'),
    #                     'subject_code': hit.entity.get('subject_code')
    #                 })

    #         return search_results

    #     except Exception as e:
    #         print(f"❌ Error searching documents: {str(e)}")
    #         raise