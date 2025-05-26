from flask_app.app.databases.milvus_connection import MilvusConnection

class DatabaseHandler:
    def __init__(self):
        pass

    def insert_document(self, document) -> int:
        """Insert embeddings into Milvus"""
        try:
            result = MilvusConnection.get_client().insert(
                collection_name="documents",
                # partition_name=document.subject_code,
                data=[document]
            )

            print(f"   ✅ Inserted document into database successfully")
            # return result['insert_count']
        except Exception as e:
            print(f"   ❌ Error inserting document into database: {str(e)}")
            raise
 
    def insert_documents(self, documents: list) -> int:
        """Insert multiple embeddings into Milvus"""
        try:
            result = MilvusConnection.get_client().upsert(
                collection_name="documents",
                data=documents  # ✅ No wrapping needed
            )

            print(f"   ✅ Upserted {len(documents)} documents into database successfully")
            return result.get('upsert_count', 0)
        except Exception as e:
            print(f"   ❌ Error upserting documents into database: {str(e)}")
            raise
