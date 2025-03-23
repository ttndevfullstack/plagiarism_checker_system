from abc import ABC, abstractmethod

class Textable(ABC):
    @abstractmethod
    def convert_embedding_to_text(self, text: str) -> str:
        """Process and return the modified text"""
        pass