#!/bin/sh
set -e

env > /app/.env

php artisan config:clear
php artisan migrate:fresh --force
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
