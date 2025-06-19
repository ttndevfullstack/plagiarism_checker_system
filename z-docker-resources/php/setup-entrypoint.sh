#!/bin/sh

# Ensure correct permissions
chown -R www-data:www-data /var/www/laravel/storage /var/www/laravel/bootstrap/cache
chmod -R 775 /var/www/laravel/storage /var/www/laravel/bootstrap/cache

# Start PHP-FPM
php-fpm -D

# Start Nginx
nginx

# Run Laravel queue worker
php artisan queue:work