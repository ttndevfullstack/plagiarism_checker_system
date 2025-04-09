from pymilvus import connections, db, MilvusClient, utility, Collection, CollectionSchema, FieldSchema, DataType
from flask_app.config import Config


class MilvusConnection:
    _instance = None
    _initialized = False

    def __new__(cls, *args, **kwargs):
        if not cls._instance:
            cls._instance = super(MilvusConnection, cls).__new__(cls)
        if not cls._initialized:
            cls._instance._initialize_connection()
            cls._initialized = True
        return cls._instance

    def _initialize_connection(self):
        """Initialize the Milvus connection."""
        try:
            MILVUS_ALIAS = Config.MILVUS_ALIAS
            MILVUS_DB_HOST = Config.MILVUS_DB_HOST
            MILVUS_DB_PORT = Config.MILVUS_DB_PORT

            # Check if connection exists and disconnect if it does
            if connections.has_connection(MILVUS_ALIAS):
                connections.disconnect(MILVUS_ALIAS)

            # Create new connection
            connections.connect(
                alias=MILVUS_ALIAS, 
                host=MILVUS_DB_HOST, 
                port=MILVUS_DB_PORT
            )
            print("✅ Connected to Milvus database.")

            # Create database if needed
            self.create_database()

            # Initialize MilvusClient
            self.client = MilvusClient(
                uri=f'http://{MILVUS_DB_HOST}:{MILVUS_DB_PORT}',
                token="", # Add token if needed
                db_name=Config.MILVUS_DB_NAME
            )
            
            # Create collection after client is initialized
            self.create_collection()

        except Exception as e:
            print(f"❌ Failed to initialize Milvus connection: {str(e)}")
            raise

    def get_connection(self):
        """Return the Milvus connection."""
        return self.client

    def close_connection(self):
        """Close the Milvus connection."""
        connections.disconnect(Config.MILVUS_ALIAS)
        print("✅ Disconnected from Milvus database.")

    def create_database(self):
        """Create a database if it doesn't exist."""
        databases = db.list_database(using=Config.MILVUS_ALIAS)
        
        if Config.MILVUS_DB_NAME not in databases:
            db.create_database(Config.MILVUS_DB_NAME, using=Config.MILVUS_ALIAS)
            print(f"✅ Created database: {Config.MILVUS_DB_NAME}")
        else:
            print(f"✅ Database {Config.MILVUS_DB_NAME} already exists")

        # Use the database
        db.using_database(Config.MILVUS_DB_NAME, using=Config.MILVUS_ALIAS)

    def create_collection(self):
        """Create collection if it doesn't exist"""
        try:
            collection_name = Config.DOCUMENT_COLLECTION_NAME
            if not self.client.has_collection(collection_name):
                fields = [
                    FieldSchema(name="id", dtype=DataType.INT64, is_primary=True, auto_id=True),
                    FieldSchema(name="document_id", dtype=DataType.INT64),
                    FieldSchema(name="subject_code", dtype=DataType.VARCHAR, max_length=100),
                    FieldSchema(name="text", dtype=DataType.VARCHAR, max_length=65535),
                    FieldSchema(name="embedding", dtype=DataType.FLOAT_VECTOR, dim=384)
                ]
                schema = CollectionSchema(fields=fields, description="Document collection")
                collection = Collection(name=collection_name, schema=schema, using=Config.MILVUS_ALIAS)
                
                index_params = {
                    "metric_type":"L2",
                    "index_type":"IVF_FLAT",
                    "params":{"nlist":1024}
                }
                collection.create_index(field_name="embedding", index_params=index_params)
                print(f"✅ Created collection {collection_name}")
            else:
                print(f"✅ Collection {collection_name} already exists")
        except Exception as e:
            print(f"❌ Error creating collection: {str(e)}")
            raise
