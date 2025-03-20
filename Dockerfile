FROM php:8.0-apache

WORKDIR /var/www/html

COPY index.php .
COPY . .

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y git unzip libzip-dev
RUN docker-php-ext-install zip

RUN composer install --no-dev

EXPOSE 80
