FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    libpq-dev libzip-dev zip unzip git curl \
    && docker-php-ext-install pdo pdo_pgsql zip mbstring

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

EXPOSE 8000

CMD php artisan config:clear && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000
