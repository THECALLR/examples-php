FROM php:5.6-apache

RUN apt-get update \
    && apt-get install -y zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY src/ /var/www/html/
COPY composer.json /var/www
WORKDIR /var/www/
RUN composer update --prefer-dist
