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
    docker-php-ext-install pcntl && \
    pecl install apcu && \
    pecl install sqlsrv && \
    pecl install pdo_sqlsrv

# ln -s /usr/bin/node /usr/local/bin/node && \
# ln -s /usr/bin/npm /usr/local/bin/npm

# npm install --location=global --unsafe-perm puppeteer@^17 && \
# chmod -R o+rx /usr/local/lib/node_modules/puppeteer/.local-chromium
# pecl install pdo_sqlsrv sqlsrv && \
# rm -rf /var/cache/apk/*

# Set environment variables for Puppeteer
# ENV PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true
# ENV PUPPETEER_EXECUTABLE_PATH=/usr/bin/chromium-browser

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

# Uncomment if needed
# ENV ENABLE_CRONTAB 1
# ENV ENABLE_HORIZON 1
# COPY ./docker-entrypoint.sh /var/www/app/docker-entrypoint.sh
# RUN chmod +x /var/www/app/docker-entrypoint.sh
# ENTRYPOINT ["sh", "/var/www/app/docker-entrypoint.sh"]

# CMD ["supervisord -n -c /etc/supervisord.conf"]
