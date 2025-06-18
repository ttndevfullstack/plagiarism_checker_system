#!/bin/sh

# Start PHP-FPM
php-fpm -D

# Start Nginx
nginx

# Run Laravel queue worker (for dev)
php artisan queue:work
