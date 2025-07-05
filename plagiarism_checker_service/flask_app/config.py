import os

basedir = os.path.abspath(os.path.dirname(__file__))

class Config:
    # Application
    ENVIRONMENT = "development"
    FILE_STORAGE_DIR = os.environ.get('FILE_STORAGE_DIR', 'flask_app/storage/uploads/files')
    ACTIVE_EMBEDDING_MODEL = os.environ.get('ACTIVE_EMBEDDING_MODEL', 'MiniLM')
    
    # Milvus
    MILVUS_ALIAS = os.environ.get('MILVUS_ALIAS', 'alias_proofly')
    MILVUS_DB_NAME = os.environ.get('MILVUS_DB_NAME', 'db_proofly')
    MILVUS_DB_HOST = os.environ.get('MILVUS_DB_HOST', 'localhost')
    MILVUS_DB_PORT = os.environ.get('MILVUS_DB_PORT', '19530')
    MILVUS_DB_USERNAME = os.environ.get('MILVUS_DB_USERNAME', 'root')
    MILVUS_DB_PASSWORD = os.environ.get('MILVUS_DB_PASSWORD', 'password')

    # Check Plagiarism
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
    IS_RANDOM_COLOR = False
    MAX_MATCHED_SOURCE = 10
    MIN_CHUNKED_TEXT_LENGTH = 3
    SIMILARITY_THRESHOLD = 0.8