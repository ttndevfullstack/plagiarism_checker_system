import os

basedir = os.path.abspath(os.path.dirname(__file__))

class Config:
    # Application
    ENVIRONMENT = "development"
    FILE_STORAGE_DIR = os.environ.get('FILE_STORAGE_DIR', 'flask_app/storage/uploads/files')
    MINILM_EMBEDDING_MODEL = os.environ.get('MINILM_EMBEDDING_MODEL', 'MiniLM')
    
    # OpenAI
    OPENAI_API_KEY = os.environ.get('OPENAI_API_KEY', 'sk-proj-saEkEV97FSSoNYMRXYl_JF0Q-HiC2rkLJDzubwil...')

    # Milvus
    MILVUS_ALIAS = os.environ.get('MILVUS_ALIAS', 'alias_proofly')
    MILVUS_DB_NAME = os.environ.get('MILVUS_DB_NAME', 'db_proofly')
    MILVUS_DB_HOST = os.environ.get('MILVUS_DB_HOST', 'localhost')
    MILVUS_DB_PORT = os.environ.get('MILVUS_DB_PORT', '19530')
    MILVUS_DB_USERNAME = os.environ.get('MILVUS_DB_USERNAME', 'root')
    MILVUS_DB_PASSWORD = os.environ.get('MILVUS_DB_PASSWORD', 'password')