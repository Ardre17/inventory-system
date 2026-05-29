#!/bin/sh
set -e

# Crear .env con variables de Railway
printenv | grep -E "^(APP_|DB_|SESSION_|CACHE_|QUEUE_)" > /app/.env

# Asegurar valores fijos
echo "SESSION_DRIVER=file" >> /app/.env
echo "CACHE_STORE=file" >> /app/.env
echo "QUEUE_CONNECTION=sync" >> /app/.env
echo "DB_CONNECTION=pgsql" >> /app/.env

php artisan config:clear
php artisan migrate:fresh --force
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
