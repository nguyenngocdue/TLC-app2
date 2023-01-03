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

RUN addgroup -g 1000 ndcgp

RUN adduser -D -u 1000 ndc -G ndcgp

# run this line manually by root accout
# RUN chown -R ndc:ndcgp /var/www/app

# USER ndc