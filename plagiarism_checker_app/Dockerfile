# ---------- Build Stage (Composer & Node) ----------
FROM php:8.3-cli AS composer-builder

# Install required PHP extensions for composer to succeed
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install intl zip gd pdo pdo_mysql mbstring

# Install Composer manually
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app
COPY ./plagiarism_checker_app /app
RUN composer install --prefer-dist --no-scripts --no-autoloader --no-interaction

FROM node:18 AS node-builder
WORKDIR /app
COPY ./plagiarism_checker_app /app
RUN npm install && npm run build


# ---------- Final Runtime Stage ----------
FROM php:8.3-fpm-alpine

# System dependencies
RUN apk add --no-cache \
    bash \
    git \
    nginx \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    shadow \
    supervisor \
    postgresql-dev \
    mysql-client \
    npm \
    nodejs \
    tzdata

# PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    gd zip pdo pdo_mysql mbstring intl opcache bcmath

# Set working directory
WORKDIR /var/www/laravel

# Copy from builders
COPY --from=composer-builder /app /var/www/laravel
COPY --from=node-builder /app/public/build /var/www/laravel/public/build

# Composer install & optimize
RUN composer install --no-dev --optimize-autoloader && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache || true

# Generate APP_KEY if not exists
RUN if [ ! -f .env ]; then cp .env.example .env; fi && \
    php artisan key:generate

# Permissions
RUN chown -R www-data:www-data /var/www/laravel && \
    chmod -R 775 storage bootstrap/cache

# Nginx config
COPY ./z-docker-resources/nginx/default.conf /etc/nginx/conf.d/default.conf

# Entrypoint
COPY ./z-docker-resources/php/setup-entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 9000 5173

CMD ["/entrypoint.sh"]
