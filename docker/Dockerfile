FROM php:7.3-fpm

WORKDIR /var/www

RUN apt-get update && docker-php-ext-install pdo_mysql && apt-get install -y --no-install-recommends git zip unzip
RUN pecl install xdebug-2.9.2 && docker-php-ext-enable xdebug

RUN echo 'xdebug.remote_port=9001' >> /usr/local/etc/php/php.ini
RUN echo 'xdebug.remote_enable=1' >> /usr/local/etc/php/php.ini
RUN echo 'xdebug.remote_connect_back=1' >> /usr/local/etc/php/php.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

CMD ["php-fpm"]
