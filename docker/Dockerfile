FROM php:7.3-fpm

WORKDIR /var/www

RUN apt-get update && docker-php-ext-install pdo_mysql && apt-get install -y --no-install-recommends git zip unzip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

CMD ["php-fpm"]
