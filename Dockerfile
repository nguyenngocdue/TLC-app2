FROM php:8.0-fpm-alpine

WORKDIR /var/www/app

RUN apk update && apk add \
    curl \
    libpng-dev \
    libxml2-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    zip \
    unzip
RUN curl -sSLf \
    -o /usr/local/bin/install-php-extensions \
    https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions && \
    chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions ldap 
RUN docker-php-ext-install pdo pdo_mysql \
    && apk --no-cache add nodejs npm

RUN docker-php-ext-install gd && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd

COPY ./configs/php.ini /usr/local/etc/php/
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# USER root
# RUN chmod 775 -R /var/www/app
# RUN chown www-data:www-data /var/www/app -R

RUN addgroup -g 1000 appgroup

RUN adduser -D -u 1000 appuser -G appgroup

RUN chown -R appuser:appgroup /var/www/app

USER appuser