#!/bin/bash
set -e

# Wait for MySQL to be fully ready
echo "Waiting for MySQL..."
max_retries=30
retry_count=0

while ! mysql -h"mysql" -u"${DB_USERNAME}" -p"${DB_PASSWORD}" -e "SELECT 1" >/dev/null 2>&1; do
    retry_count=$((retry_count+1))
    if [ $retry_count -ge $max_retries ]; then
        echo "MySQL is not available after $max_retries attempts!"
        exit 1
    fi
    echo "MySQL not ready yet. Retry $retry_count/$max_retries..."
    sleep 2
done

# Clear and optimize Laravel
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Run migrations and seed
php artisan migrate --force
php artisan db:seed --force

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

exec "$@"