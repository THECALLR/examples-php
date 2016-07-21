FROM php:5.6-cli

RUN apt-get update \
    && apt-get install -y zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && mkdir /scripts 

COPY scripts /scripts/

WORKDIR /scripts/

RUN composer update --prefer-dist

