from flask import current_app
from pymilvus import Collection, CollectionSchema, FieldSchema, DataType, Index
from flask_app.config import Config

# Get Milvus client connection
client = current_app.milvus_connection.get_connection()

# Define fields for the schema
fields = [
    FieldSchema(name="id", datatype=DataType.INT64, is_primary=True, auto_id=True),
    FieldSchema(name="source_text", datatype=DataType.VARCHAR, max_length=65535),
    FieldSchema(name="url", datatype=DataType.VARCHAR, max_length=2048, is_nullable=True, default=None),
    FieldSchema(name="embedding", datatype=DataType.FLOAT_VECTOR, dim=768),
]

# Define schema
schema = CollectionSchema(fields, description="Collection for storing documents with embeddings.")

# Create the collection
collection = Collection(name=Config.DOCUMENT_COLLECTION_NAME, schema=schema, using=client.alias)

# Create an index for the embedding field
index = Index(
    collection,
    field_name="embedding",
    index_type="IVF_FLAT",
    metric_type="IP",
    params={"nlist": 128}
)

# Load the collection for queries
collection.load()
