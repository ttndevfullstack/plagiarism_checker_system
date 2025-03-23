from pymilvus import connections, db
from flask_app.config import Config


class MilvusConnection:
    _instance = None

    def __new__(cls, *args, **kwargs):
        if not cls._instance:
            cls._instance = super(MilvusConnection, cls).__new__(cls)
            cls._instance._initialize_connection()
        return cls._instance

    def _initialize_connection(self):
        """Initialize the Milvus connection."""
        MILVUS_ALIAS = Config.MILVUS_ALIAS
        MILVUS_DB_HOST = Config.MILVUS_DB_HOST
        MILVUS_DB_PORT = Config.MILVUS_DB_PORT

        # Connect to Milvus
        connections.connect(
            alias=MILVUS_ALIAS, host=MILVUS_DB_HOST, port=MILVUS_DB_PORT
        )
        print("✅ Connected to Milvus database.")

        if not connections.has_connection(MILVUS_ALIAS):
            raise RuntimeError("❌ Failed to establish connection to Milvus.")

        # self.create_database()

    def get_connection(self):
        """Return the Milvus connection."""
        return connections.get_connection(Config.MILVUS_ALIAS)

    def close_connection(self):
        """Close the Milvus connection."""
        connections.disconnect(Config.MILVUS_ALIAS)
        print("✅ Disconnected from Milvus database.")

    def get_db(self):
        """Return the Milvus db."""
        return connections.get_connection(Config.MILVUS_ALIAS)

    def create_database(self):
        """Create a database if it doesn't exist."""
        # client = self.get_connection()
        # client.list
        # # databases = db.list_database(using=Config.MILVUS_ALIAS)

        # if Config.MILVUS_DB_NAME not in databases:
        #     db.create_database(Config.MILVUS_DB_NAME)
        #     print(f"✅ Created database: {Config.MILVUS_DB_NAME}")
        # else:
        #     print(f"✅ Database {Config.MILVUS_DB_NAME} already exists")

        # # Use the database
        # db.using_database(Config.MILVUS_DB_NAME)
