FROM php:8.0-apache

RUN echo "ServerName 127.0.0.1" >> /etc/apache2/apache2.conf
RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . /var/www/html/


EXPOSE 80
