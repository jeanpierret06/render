FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    pkg-config \
    libssl-dev \
    ca-certificates

RUN update-ca-certificates

# Instalar extensión nativa de MongoDB para PHP 8.2
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /var/www/html

# Copiar los archivos del repositorio al contenedor
COPY . .

# SOLUCIÓN DIRECTA EN LA NUBE: Borrar vendor o lock viejos que se hayan subido
# y forzar a Composer a descargar la versión limpia y compatible con PHP 8.2
RUN rm -rf vendor composer.lock \
    && composer require mongodb/mongodb --no-interaction

EXPOSE 80
