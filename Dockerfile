FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev libpq-dev \
    && docker-php-ext-install zip pdo pdo_mysql pdo_pgsql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
