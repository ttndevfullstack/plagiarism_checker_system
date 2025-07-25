from flask_app.app.databases.milvus_connection import MilvusConnection

class DatabaseHandler:
    def __init__(self):
        pass

    def delete_documents_by_document_id(self, document_id: int) -> int:
        """Delete all records from Milvus by document_id"""
        try:
            result = MilvusConnection.get_client().delete(
                collection_name="documents",
                filter=f"document_id == {document_id}"
            )
            print(f"   ✅ Deleted documents with document_id={document_id} from database successfully")
            return result.get('delete_count', 0)
        except Exception as e:
            print(f"   ❌ Error deleting documents from database: {str(e)}")
            raise

    def get_all_documents_basic_info(self):
        """Fetch all document_id and original_name from Milvus documents collection"""
        try:
            results = MilvusConnection.get_client().query(
                collection_name="documents",
                output_fields=["document_id", "original_name"]
            )
            print(f"   ✅ Fetched {len(results)} documents from database successfully")
            return results
        except Exception as e:
            print(f"   ❌ Error querying documents from database: {str(e)}")
            raise

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

            print(f"   ✅ Inserted {len(documents)} documents into database successfully")
            return result.get('upsert_count', 0)
        except Exception as e:
            print(f"   ❌ Error inserting documents into database: {str(e)}")
            raise
