# Use PHP with Apache
FROM php:8.3-apache

ARG APP_KEY
ARG APP_URL
ENV APP_KEY=$APP_KEY
ENV APP_URL=$APP_URL


# Set working directory
WORKDIR /var/www/html

# Install system dependencies
# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    build-essential \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libssl-dev \
    libicu-dev \
    libsasl2-dev \
    pkg-config \
    zip \
    unzip \
    nodejs \
    npm \
    sqlite3 \
    libsqlite3-dev \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Install Bun
RUN curl -fsSL https://bun.sh/install | bash
ENV PATH="/root/.bun/bin:$PATH"

# Install PHP extensions including MongoDB and intl
RUN docker-php-ext-install pdo_mysql pdo_sqlite pdo_pgsql mbstring exif pcntl bcmath gd zip intl

# Install MongoDB extension using PECL (PECL is already included in PHP Docker image)
RUN pecl channel-update pecl.php.net \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache 

RUN chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/storage/logs \
    && chmod -R 775 /var/www/html/storage/framework \
    && chmod -R 775 /var/www/html/storage/framework/cache \
    && chmod -R 775 /var/www/html/storage/framework/views \
    && chmod -R 775 /var/www/html/storage/framework/sessions

RUN echo "upload_max_filesize=50M" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size=50M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "max_file_uploads=20" >> /usr/local/etc/php/conf.d/uploads.ini


# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Build assets
RUN bun install && bun run build
RUN php artisan filament:assets

# Optimize Laravel application
RUN php artisan filament:cache
RUN php artisan icon:cache
RUN php artisan view:cache
RUN php artisan route:cache

# Optimize Autoload
RUN composer dump-autoload -o


# Configure Apache
RUN a2enmod rewrite
COPY ./docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf

# Expose port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
