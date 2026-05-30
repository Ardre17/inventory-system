#!/bin/sh
set -e

printf "APP_ENV=production\n" > /app/.env
printf "APP_DEBUG=false\n" >> /app/.env
printf "APP_URL=https://inventory-system-production-a650.up.railway.app\n" >> /app/.env
printf "DB_CONNECTION=pgsql\n" >> /app/.env
printf "DB_HOST=%s\n" "$DB_HOST" >> /app/.env
printf "DB_PORT=%s\n" "${DB_PORT:-5432}" >> /app/.env
printf "DB_DATABASE=%s\n" "$DB_DATABASE" >> /app/.env
printf "DB_USERNAME=%s\n" "$DB_USERNAME" >> /app/.env
printf "DB_PASSWORD=%s\n" "$DB_PASSWORD" >> /app/.env
printf "SESSION_DRIVER=file\n" >> /app/.env
printf "CACHE_STORE=file\n" >> /app/.env
printf "QUEUE_CONNECTION=sync\n" >> /app/.env
printf "LOG_CHANNEL=stderr\n" >> /app/.env

php artisan key:generate --force
php artisan config:clear
php artisan migrate:fresh --force
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
