FROM php:8.0-fpm-alpine

WORKDIR /var/www/app

RUN apk update && apk add \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip
RUN curl -sSLf \
    -o /usr/local/bin/install-php-extensions \
    https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions && \
    chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions ldap

RUN docker-php-ext-install pdo pdo_mysql \
    && apk --no-cache add nodejs npm
COPY ./configs/php.ini /usr/local/etc/php/
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

USER root

RUN chmod 777 -R /var/www/app