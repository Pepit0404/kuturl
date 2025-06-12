FROM php:8.0-apache

WORKDIR /var/www/html

COPY ./ .env /var/www/html/

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN a2enmod rewrite

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y git unzip libzip-dev
RUN docker-php-ext-install zip

RUN composer install --no-dev

EXPOSE 80
