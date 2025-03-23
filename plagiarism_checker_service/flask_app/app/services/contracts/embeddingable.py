from abc import ABC, abstractmethod

class Embeddingable(ABC):
    @abstractmethod
    def convert_text_to_embedding(self, text):
        """Convert text to embedding representation"""
        pass