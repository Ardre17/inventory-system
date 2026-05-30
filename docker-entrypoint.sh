#!/bin/sh
set -e

# Crear .env solo con las variables necesarias
cat > /app/.env << ENVFILE
APP_ENV=production
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-https://inventory-system-production-a650.up.railway.app}
DB_CONNECTION=pgsql
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT:-5432}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
LOG_CHANNEL=stderr
ENVFILE

php artisan key:generate --force
php artisan config:clear
php artisan migrate:fresh --force
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
