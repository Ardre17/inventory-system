#!/bin/sh
set -e

cat > /app/.env << ENVEOF
APP_KEY=base64:Hr95U5K6CoBgi6vOWWn6Ei4COZtbM22nemOlwJMzoOc=
APP_ENV=production
APP_DEBUG=false
APP_URL=https://inventory-system-production-a650.up.railway.app
DB_CONNECTION=pgsql
DB_HOST=$DB_HOST
DB_PORT=${DB_PORT:-5432}
DB_DATABASE=$DB_DATABASE
DB_USERNAME=$DB_USERNAME
DB_PASSWORD=$DB_PASSWORD
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
LOG_CHANNEL=stderr
ENVEOF

php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan migrate:fresh --force
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
