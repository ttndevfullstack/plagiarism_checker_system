from flask_app.config import Config
from flask_app.app.services.openai_embedding_service import OpenAIEmbeddingService
from flask_app.app.services.minilm_embedding_service import MiniLMEmbeddingService

class EmbeddingModelFactory:
    @staticmethod
    def get_model(model_name: str):
        if model_name == Config.OPENAI_EMBEDDING_MODEL:
            return OpenAIEmbeddingService()
        elif model_name == Config.MINILM_EMBEDDING_MODEL:
            return MiniLMEmbeddingService()
        else:
            raise ValueError(f'Unknown model name: {model_name}')