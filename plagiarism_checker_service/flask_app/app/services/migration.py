from flask_app.app.databases.migrations.add_documents_schema import DocumentMigration
class Migration:
  @staticmethod
  def run():
    try:
      migration = DocumentMigration() 
      migration.up()
      # migration.down()

      return True
    except Exception as e:
      return False