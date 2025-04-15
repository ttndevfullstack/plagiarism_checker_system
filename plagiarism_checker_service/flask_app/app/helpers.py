from flask_app.config import Config

class Helper:
  @staticmethod
  def isProduction() -> bool:
      return Config.ENVIRONMENT == 'production'

  @staticmethod
  def isStaging() -> bool:
      return Config.ENVIRONMENT == 'staging'

  @staticmethod
  def isDevelopment() -> bool:
      return Config.ENVIRONMENT == 'development'