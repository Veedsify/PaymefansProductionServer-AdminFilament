FROM php:8.3-fpm AS php

WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libpq-dev sqlite3 libsqlite3-dev \
    libssl-dev libicu-dev libsasl2-dev pkg-config build-essential \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql pdo_sqlite pdo_pgsql mbstring exif bcmath gd zip intl

# MongoDB extension
RUN pecl install mongodb && docker-php-ext-enable mongodb

# PHP upload limits
RUN echo "upload_max_filesize=100M" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size=100M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "memory_limit=512M" >> /usr/local/etc/php/conf.d/uploads.ini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy Laravel app
COPY . .

# Install backend dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Bun
RUN curl -fsSL https://bun.sh/install | bash
ENV PATH="/root/.bun/bin:$PATH"

# Build frontend assets
RUN bun install && bun run build

# Laravel cache
RUN php artisan storage:link || true
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan filament:assets \
    && php artisan filament:cache \
    && php artisan icon:cache \
    && composer dump-autoload -o

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache


# -----------------------------
# 2. Final Caddy image
# -----------------------------
FROM caddy:2-alpine AS server

WORKDIR /var/www/html

# Copy app from PHP builder
COPY --from=php /var/www/html /var/www/html

# Copy Caddyfile
COPY Caddyfile /etc/caddy/Caddyfile

EXPOSE 80
CMD ["caddy", "run", "--config", "/etc/caddy/Caddyfile"]
