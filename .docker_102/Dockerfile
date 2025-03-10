FROM php:8.2-fpm-alpine3.17

WORKDIR /var/www/app

# Add the community repository
RUN echo "http://dl-cdn.alpinelinux.org/alpine/v3.17/community" >> /etc/apk/repositories

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

COPY ./configs/php.ini /usr/local/etc/php/

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

COPY ./supervisord.conf /etc/supervisord.conf
COPY ./supervisor.d /etc/supervisor.d

COPY ./sqlserver/installODBC /home/ndc/
RUN chmod +x /home/ndc/installODBC
RUN /home/ndc/installODBC

#RUN addgroup -g 1000 ndcgp
#RUN adduser -D -u 1000 ndc -G ndcgp
#RUN chown -R ndc:ndcgp /var/www/app
#USER ndc

# RUN usermod -u 82 www-data
RUN chown -R www-data:www-data .

# ENV ENABLE_CRONTAB 1
# ENV ENABLE_HORIZON 1

# ENTRYPOINT ["sh", "/var/www/app/docker-entrypoint.sh"]

# CMD supervisord -n -c /etc/supervisord.conf