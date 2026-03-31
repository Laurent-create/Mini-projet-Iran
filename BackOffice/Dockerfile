FROM php:8.3-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libwebp-dev \
    libmagickwand-dev \
    imagemagick \
    && docker-php-ext-install pdo_pgsql gd \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && a2enmod rewrite \
    && sed -ri 's#<Directory /var/www/>#<Directory /var/www/>\n\tAllowOverride All#' /etc/apache2/apache2.conf \
    && printf "ServerName localhost\n" > /etc/apache2/conf-available/servername.conf \
    && a2enconf servername \
    && rm -rf /var/lib/apt/lists/*
