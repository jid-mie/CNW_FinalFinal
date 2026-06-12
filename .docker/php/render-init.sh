#!/bin/sh

# Exit immediately if a command exits with a non-zero status
set -e

echo "🚀 Running production initialization..."

# Run migrations
if [ -n "$DB_HOST" ]; then
    echo "Running database migrations..."
    php artisan migrate --force
fi

# Cache configuration, routes, and views for production performance
echo "Caching configuration and routes..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🚀 Initialization complete!"
