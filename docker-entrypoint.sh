#!/bin/sh
set -e

env > /app/.env
echo "DB_CONNECTION=pgsql" >> /app/.env

php artisan key:generate --force
php artisan config:clear
php artisan migrate:fresh --force
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
