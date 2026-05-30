#!/bin/sh
set -e

# Agregar variables de DB al .env existente
printf "DB_CONNECTION=pgsql\n" >> /app/.env
printf "DB_HOST=%s\n" "$DB_HOST" >> /app/.env
printf "DB_PORT=%s\n" "${DB_PORT:-5432}" >> /app/.env
printf "DB_DATABASE=%s\n" "$DB_DATABASE" >> /app/.env
printf "DB_USERNAME=%s\n" "$DB_USERNAME" >> /app/.env
printf "DB_PASSWORD=%s\n" "$DB_PASSWORD" >> /app/.env
printf "APP_URL=https://inventory-system-production-a650.up.railway.app\n" >> /app/.env

php artisan config:clear
php artisan migrate:fresh --force
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
