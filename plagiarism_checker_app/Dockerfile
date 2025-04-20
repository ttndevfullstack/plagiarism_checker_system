FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/laravel

# 1. First copy ONLY the files needed for composer install
COPY composer.json composer.lock artisan app/Helpers.php ./

# 2. Install dependencies (without running scripts)
RUN composer install --ignore-platform-reqs --no-scripts

# 3. Copy the entire application
COPY . .

# 4. Now run the composer scripts with the full application in place
RUN composer run-script post-autoload-dump

# Change ownership
RUN chown -R www-data:www-data /var/www/laravel