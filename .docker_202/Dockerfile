FROM php:8.2-fpm-alpine3.17

WORKDIR /var/www/app

# Add the community repository
RUN echo "http://dl-cdn.alpinelinux.org/alpine/v3.17/community" >> /etc/apk/repositories

# Install necessary packages and PHP extensions
RUN apk update && apk add --no-cache \
    curl \
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
    # build-base \
    # autoconf \
    # nodejs \
    # npm && \
    gnupg \
    sudo \
    unixodbc-dev

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

COPY ./configs/php.ini /usr/local/etc/php/

# Copy Composer from the official Composer image
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copy Supervisor configuration
COPY ./supervisord.conf /etc/supervisord.conf
COPY ./supervisor.d /etc/supervisor.d

COPY ./sqlserver/installODBC /home/ndc/
RUN chmod +x /home/ndc/installODBC
RUN /home/ndc/installODBC

# Adjust permissions and ownership
RUN chown -R www-data:www-data /var/www/app

# Uncomment if needed
# ENV ENABLE_CRONTAB 1
# ENV ENABLE_HORIZON 1

# ENTRYPOINT ["sh", "/var/www/app/docker-entrypoint.sh"]

# CMD ["supervisord", "-n", "-c", "/etc/supervisord.conf"]
