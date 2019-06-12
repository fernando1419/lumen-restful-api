# https://hub.docker.com/_/php

FROM php:7.3-fpm-alpine

LABEL maintainer="Fernando Lamas <lamas.fernando1419@gmail.com>"

RUN apk update && \
    apk upgrade && \
    apk add findutils curl openrc nano postgresql-dev bash openssh

# Install Xdebug and other php extensions
RUN apk add --virtual _build-deps $PHPIZE_DEPS && \
    pecl install xdebug-2.7.0 && \
    docker-php-ext-enable xdebug && \
    docker-php-ext-install pdo_mysql pdo_pgsql && \
    apk del _build-deps && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* /var/cache/apk/*

COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /usr/share/nginx/html

EXPOSE 9000

CMD ["php-fpm"]

