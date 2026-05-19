#!/bin/sh

set -e

if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate
fi

if [ ! -d vendor ]; then
    composer install --no-interaction
fi

php artisan optimize:clear

php artisan migrate --force

exec "$@"
