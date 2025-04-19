from flask_app.app.databases.migrations.add_documents_schema import DocumentMigration
class Migration:
  @staticmethod
  def up():
    try:
      migration = DocumentMigration() 
      migration.up()

      return True
    except Exception as e:
      return False
  
  @staticmethod
  def down():
    try:
      migration = DocumentMigration() 
      migration.down()

      return True
    except Exception as e:
      return False