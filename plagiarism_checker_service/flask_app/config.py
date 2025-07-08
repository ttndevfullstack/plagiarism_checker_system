import os

basedir = os.path.abspath(os.path.dirname(__file__))

class Config:
    # ===========================================
    # System
    # ===========================================
    ENVIRONMENT = "development"
    FILE_STORAGE_DIR = os.environ.get('FILE_STORAGE_DIR', 'flask_app/storage/uploads/files')
    

    # ===========================================
    # Milvus Database
    # ===========================================
    MILVUS_ALIAS = os.environ.get('MILVUS_ALIAS', 'alias_proofly')
    MILVUS_DB_NAME = os.environ.get('MILVUS_DB_NAME', 'db_proofly')
    MILVUS_DB_HOST = os.environ.get('MILVUS_DB_HOST', 'localhost')
    MILVUS_DB_PORT = os.environ.get('MILVUS_DB_PORT', '19530')
    MILVUS_DB_USERNAME = os.environ.get('MILVUS_DB_USERNAME', 'root')
    MILVUS_DB_PASSWORD = os.environ.get('MILVUS_DB_PASSWORD', 'password')


    # ===========================================
    # Embedding Model
    # ===========================================
    ACTIVE_EMBEDDING_MODEL = os.environ.get('ACTIVE_EMBEDDING_MODEL', 'MiniLM')
    ACTIVE_EMBEDDING_MODEL_NAME = os.environ.get('ACTIVE_EMBEDDING_MODEL_NAME', 'sentence-transformers/LaBSE')
    

    # ===========================================
    # Plagiarism Checker
    # ===========================================
    # Color for highlighting matched text in the UI
    HIGHLIGHT_COLORS = {
        0: (1.0, 0.46, 0.36),   # Red
        1: (0.98, 0.74, 0.14),  # Orange
        2: (0.19, 0.81, 0.32),  # Green
        3: (0.26, 0.59, 1.0),   # Blue
        4: (0.78, 0.17, 0.89),  # Purple
        5: (1.0, 0.24, 0.52),   # Pink
        6: (0.12, 0.77, 0.83),  # Cyan
        7: (0.62, 0.45, 0.24),  # Brown
        8: (1.0, 0.88, 0.2),    # Yellow
        9: (0.58, 0.58, 0.58),  # Gray
    }

    # If True, each matched source will have a random color from the HIGHLIGHT_COLORS
    IS_RANDOM_COLOR = False

    # Maximum number of sources to return. Ex: only return top 10 matched sources
    MAX_MATCHED_SOURCE = 10

    # Minimum number of words in a chunked text to be considered valid
    MIN_CHUNKED_TEXT_WORD = 3

    # Minimum similarity percentage to consider a match
    SIMILARITY_THRESHOLD = 0.75

    # Minimum length of a paragraph to be considered valid
    MIN_PARAGRAPH_LENGTH = 50