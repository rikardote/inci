FROM php:7.0-fpm

RUN apt-get update && apt-get install -y \
    git \
    libzip-dev \
    zip \
    nano \
    unzip

RUN docker-php-ext-configure zip --with-libzip

RUN docker-php-ext-install pdo_mysql zip

RUN curl --silent --show-error https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer
