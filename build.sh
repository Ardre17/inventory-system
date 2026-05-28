#!/usr/bin/env bash

# Instalar PHP y Composer
apt-get update
apt-get install -y php8.2 php8.2-cli php8.2-pgsql php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip

# Instalar Composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# Instalar dependencias
composer install --no-dev --optimize-autoloader

# Laravel setup
php artisan config:clear
php artisan migrate --force
php artisan storage:link
