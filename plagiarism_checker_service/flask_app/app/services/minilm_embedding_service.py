from sentence_transformers import SentenceTransformer

from flask_app.app.services.contracts.textable import Textable
from flask_app.app.services.contracts.embeddingable import Embeddingable

class MiniLMEmbeddingService(Embeddingable, Textable):
    def __init__(self):
        self.model = SentenceTransformer('sentence-transformers/all-MiniLM-L6-v2')

    def convert_text_to_embedding(self, text: str):
        """Generate embeddings using MiniLM Model"""
        print("   👉 Convert text to embedding")
        
        embedding = self.model.encode(text)
        return embedding
    
    def convert_embedding_to_text(self, text):
        """Generate text using MiniLM Model"""
        print("   👉 Convert embedding to text")