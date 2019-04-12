FROM php:7-fpm-alpine

MAINTAINER Fernando Lamas <lamas.fernando1419@gmail.com>

RUN apk --no-cache update && \
    apk --no-cache upgrade && \
    apk add --no-cache findutils curl openrc nano postgresql-dev

# Install Xdebug and other php extensions
RUN apk add --no-cache --virtual _build-deps $PHPIZE_DEPS && \
    pecl install xdebug-2.7.0 && \
    docker-php-ext-enable xdebug && \
    docker-php-ext-install pdo_mysql pdo_pgsql && \
    apk del _build-deps && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /usr/share/nginx/html

EXPOSE 9000

CMD ["php-fpm"]

