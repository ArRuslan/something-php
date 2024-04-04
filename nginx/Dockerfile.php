FROM php:fpm-alpine

RUN docker-php-ext-install mysqli

COPY *.php /var/www/html/
COPY ../assets /var/www/html/assets
COPY ../pages /var/www/html/pages
