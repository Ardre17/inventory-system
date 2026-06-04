#!/bin/sh
set -e

# Run migrations
php artisan migrate --force

# Start Laravel server
exec php artisan serve --host=0.0.0.0 --port=8000
