FROM php:7.4-fpm

# Instala las extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    zip \
    unzip \
    && docker-php-ext-install -j$(nproc) iconv mysqli pdo pdo_mysql \
    && docker-php-ext-install -j$(nproc) gd

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia los archivos de tu aplicación
COPY . /var/www/html

# Instala las dependencias de Composer
RUN composer install

# Expone el puerto 80
EXPOSE 80

# Comando para iniciar PHP-FPM
CMD ["php-fpm"]
