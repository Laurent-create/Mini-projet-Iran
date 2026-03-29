FROM php:8.3-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev libjpeg62-turbo-dev libpng-dev libwebp-dev \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo_pgsql \
    && a2enmod rewrite headers expires deflate \
    && sed -ri 's#<Directory /var/www/>#<Directory /var/www/>\n\tAllowOverride All#' /etc/apache2/apache2.conf \
    && printf "ServerName localhost\n" > /etc/apache2/conf-available/servername.conf \
    && a2enconf servername \
    && rm -rf /var/lib/apt/lists/*
