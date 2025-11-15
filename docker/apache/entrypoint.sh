#!/bin/bash
set -e

echo "Starting Laravel application..."

# Ensure storage directories exist with correct permissions
mkdir -p /var/www/html/storage/app/livewire-tmp
mkdir -p /var/www/html/storage/app/public
chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage

# Clear any cached configs (important for runtime env vars like AWS credentials)
php artisan config:clear
php artisan cache:clear

# Now cache with runtime environment variables available
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan filament:cache
php artisan icon:cache

# Optimize autoloader
composer dump-autoload -o

echo "Laravel application ready!"

# Execute the CMD (apache2-foreground)
exec "$@"