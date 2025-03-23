from openai import OpenAI
from flask_app.config import Config

from flask_app.app.services.contracts.textable import Textable
from flask_app.app.services.contracts.embeddingable import Embeddingable

class OpenAIEmbeddingService(Embeddingable, Textable):
    def __init__(self):
        self.client = OpenAI(api_key=Config.OPENAI_API_KEY)

    def convert_text_to_embedding(self, text):
        """Generate embeddings using OpenAI API"""
        response = self.client.embeddings.create(
            model="text-embedding-ada-002",
            input=text,
            encoding_format="float"
        )
        return response.data[0].embedding
    
    def convert_embedding_to_text(self, text):
        """Generate text using OpenAI API"""