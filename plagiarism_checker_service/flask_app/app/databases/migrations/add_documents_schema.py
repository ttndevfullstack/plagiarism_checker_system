from pymilvus import MilvusClient, DataType
from flask_app.app.databases.milvus_connection import MilvusConnection

class DocumentMigration:
    def __init__(self):
        self.client = MilvusConnection.get_client()

    def create_schemas(self):
        schema = MilvusClient.create_schema(
            auto_id=False,  # Change to False to use custom document_id
            enable_dynamic_field=True,
        )
        
        # Add fields to schema
        schema.add_field(field_name="document_id", datatype=DataType.INT64, is_primary=True, auto_id=False)
        schema.add_field(field_name="subject_code", datatype=DataType.VARCHAR, max_length=100)
        schema.add_field(field_name="embedding", datatype=DataType.FLOAT_VECTOR, dim=384)
        
        return schema
    
    def create_indexes(self):
        index_params = self.client.prepare_index_params()

        index_params.add_index(field_name="document_id", index_type="AUTOINDEX")

        # Index for embedding field for similarity search
        index_params.add_index(
            field_name="embedding",
            index_name="embedding_index",
            index_type="IVF_FLAT",   # Good balance between accuracy and speed
            metric_type="COSINE",    # Best for plagiarism detection (text similarity)
            params={
                "nlist": 1024,       # Number of clusters (adjust based on collection size)
                "nprobe": 16         # Number of clusters to search (adjust for speed/recall)
            }
        )

        return index_params

    def up(self):
        """Run the migrations"""
        try:
            collections = self.client.list_collections()

            if "documents" in collections:
                print("❌ Collection documents already exists in database")
                return

            schema = self.create_schemas()
            index_params = self.create_indexes()

            self.client.create_collection(
                collection_name="documents",
                schema=schema,
                index_params=index_params,
            )

            print("✅ Migration up completed successfully")
            
        except Exception as e:
            print(f"❌ Migration up failed: {str(e)}")
            raise

    def down(self):
        """Reverse the migrations"""
        try:
            collections = self.client.list_collections()
            
            if "documents" not in collections:
                print("❌ Collection documents does not exist in database")
                return
                
            self.client.drop_collection("documents")
            print("✅ Migration down completed successfully")
            
        except Exception as e:
            print(f"❌ Migration down failed: {str(e)}")
            raise

def main():
    migration = DocumentMigration()
    # migration.up()
    migration.down()
        
if __name__ == "__main__":
    main()