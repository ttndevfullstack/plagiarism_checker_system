import numpy as np
from sentence_transformers import SentenceTransformer

from flask_app.config import Config
from flask_app.app.services.contracts.textable import Textable
from flask_app.app.services.contracts.embeddingable import Embeddingable

class MiniLMEmbeddingService(Embeddingable, Textable):
    def __init__(self):
        self.model = SentenceTransformer(Config.ACTIVE_EMBEDDING_MODEL_NAME)

    def convert_text_to_embedding(self, text: str):
        """Generate embeddings using MiniLM Model"""
        embedding = self.model.encode(text)
        # Ensure that the output of MiniLM is normalized to unit vectors
        embedding = embedding / np.linalg.norm(embedding)
        
        return embedding
    
    def convert_embedding_to_text(self, text):
        """Generate text using MiniLM Model"""
        print("   👉 Convert embedding to text")