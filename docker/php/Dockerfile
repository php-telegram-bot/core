FROM php:8.1-fpm

COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN set -ev; \
    apt-get update; \
    apt-get install -y git; \
    install-php-extensions zip pdo_mysql xdebug

WORKDIR /app
