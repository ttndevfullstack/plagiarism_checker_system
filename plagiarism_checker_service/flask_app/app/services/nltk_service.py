import os
import shutil
import nltk
import zipfile

class NLTKService:
    # Place nltk_data one level above the current directory
    NLTK_DATA_DIR = os.path.abspath(os.path.join(os.path.dirname(__file__), '../..', 'nltk_data'))
    NLTK_RESOURCES = [
        'punkt',
        'stopwords',
        'wordnet',
        'omw-1.4'
    ]

    @classmethod
    def _insert_nltk_path(cls):
        if cls.NLTK_DATA_DIR not in nltk.data.path:
            nltk.data.path.insert(0, cls.NLTK_DATA_DIR)

    @classmethod
    def nltk_data_exists(cls):
        """Check if the nltk_data directory exists and is not empty."""
        print(f"Checking NLTK data directory: {cls.NLTK_DATA_DIR}")
        cls._insert_nltk_path()
        if not os.path.isdir(cls.NLTK_DATA_DIR):
            return False
        # Check if directory is not empty
        if not os.listdir(cls.NLTK_DATA_DIR):
            return False
        return True

    @classmethod
    def download_nltk_data(cls):
        """Download required NLTK resources to the nltk_data directory if not already present."""
        cls._insert_nltk_path()
        os.makedirs(cls.NLTK_DATA_DIR, exist_ok=True)
        for resource in cls.NLTK_RESOURCES:
            try:
                if resource == 'punkt':
                    nltk.data.find('tokenizers/punkt')
                else:
                    nltk.data.find(f'corpora/{resource}')
            except (LookupError, zipfile.BadZipFile, OSError):
                # If corrupted, delete and re-download everything
                cls.delete_nltk_data()
                os.makedirs(cls.NLTK_DATA_DIR, exist_ok=True)
                nltk.download(resource, download_dir=cls.NLTK_DATA_DIR, quiet=True)

    @classmethod
    def delete_nltk_data(cls):
        """Delete the nltk_data directory and all its contents."""
        if os.path.isdir(cls.NLTK_DATA_DIR):
            shutil.rmtree(cls.NLTK_DATA_DIR)

    @classmethod
    def ensure_nltk_data(cls):
        """Ensure nltk_data exists and all resources are downloaded and valid."""
        if not cls.nltk_data_exists():
            cls.delete_nltk_data()
            cls.download_nltk_data()
