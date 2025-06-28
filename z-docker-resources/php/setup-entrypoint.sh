#!/bin/sh

cd /var/www/laravel

# Fix old mistake: remove `.env` if it was wrongly created as a directory
if [ -d .env ]; then
  echo ".env is a directory, removing it to recreate..."
  rm -rf .env
fi

# Create .env if not exists
if [ ! -f .env ] && [ -f .env.example ]; then
  echo "Creating .env from .env.example"
  cp .env.example .env
fi

# Install dependecy
if [ ! -f vendor/autoload.php ]; then
  composer install
fi

# Generate app key if not set
if ! grep -q '^APP_KEY=' .env; then
  echo "Generating Laravel APP_KEY..."
  php artisan key:generate || true
fi

# Ensure correct permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
chmod 755 /var/www/laravel/storage/app/public/downloads

# Link storage to public
php artisan storage:link || true

# Migrate and seed data
INIT_FILE="/var/www/laravel/.initialized"
if [ ! -f "$INIT_FILE" ]; then
  echo "First-time setup: migrate and seed"
  php artisan migrate --force && php artisan db:seed --force
  touch "$INIT_FILE"
else
  echo "Already initialized, skipping migrate and seed"
fi

# Start PHP-FPM
php-fpm -D

# Start Nginx
nginx

# Run Laravel queue worker
php artisan queue:work
