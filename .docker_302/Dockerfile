# Use the official PHP image with FPM and Alpine
FROM php:8.2-fpm-alpine3.17

# Set the working directory
WORKDIR /var/www/app

# Add the community repository
RUN echo "http://dl-cdn.alpinelinux.org/alpine/v3.17/community" >> /etc/apk/repositories

# Install necessary packages and PHP extensions
RUN apk update && apk add --no-cache \
    build-base \
    shadow \
    curl \
    libzip-dev \
    libpng-dev \
    libxml2-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    zip \
    unzip \
    nano \
    mc \
    htop \
    supervisor \
    php-zip \
    autoconf \
    gnupg \
    sudo \
    unixodbc-dev \
    # for Spatie\PdfToText\Pdf;
    poppler-utils

RUN curl -sSLf -o /usr/local/bin/install-php-extensions https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions && \
    chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions ldap && \
    docker-php-ext-install pdo pdo_mysql && \
    docker-php-ext-install gd && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd && \
    docker-php-ext-install zip && \
    docker-php-ext-install pcntl

RUN pecl install apcu && \
    pecl install sqlsrv && \
    pecl install pdo_sqlsrv

# This section need to be seperated for MongoDB Client support SSL
RUN apk update && \
    apk add --no-cache \
    openssl-dev \
    autoconf \
    g++ \
    make \
    libtool \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb


# Copy PHP configuration file
COPY ./configs/php.ini /usr/local/etc/php/

# Copy Composer from the official Composer image
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copy Supervisor configuration
COPY ./supervisord.conf /etc/supervisord.conf
COPY ./supervisor.d /etc/supervisor.d

COPY ./sqlserver/installODBC /home/ndc/
RUN chmod +x /home/ndc/installODBC
RUN /home/ndc/installODBC

# Add and configure user
RUN addgroup -g 1000 ndcgp && \    
    adduser -D -u 1000 ndc -G ndcgp && \            
    chown -R ndc:ndcgp /var/www/app

# Switch to the new user
USER ndc
