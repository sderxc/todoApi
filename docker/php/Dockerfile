FROM php:7.4-alpine

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY --from=composer:2.0.9 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

CMD docker/php/entrypoint.sh