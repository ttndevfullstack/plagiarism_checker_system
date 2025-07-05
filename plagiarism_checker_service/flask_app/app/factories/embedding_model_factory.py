from flask_app.config import Config
from flask_app.app.services.minilm_embedding_service import MiniLMEmbeddingService

class EmbeddingModelFactory:
    @staticmethod
    def get_model(model_name: str):
        if model_name == Config.ACTIVE_EMBEDDING_MODEL:
            return MiniLMEmbeddingService()
        else:
            raise ValueError(f'Unknown model name: {model_name}')