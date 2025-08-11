FROM php:8.3-fpm

ARG DEBIAN_FRONTEND=noninteractive

RUN set -eux; \
  apt-get update; \
  apt-get install -y --no-install-recommends git unzip zip libpq-dev libzip-dev ca-certificates curl autoconf build-essential; \
  docker-php-ext-install -j"$(nproc)" bcmath pdo pdo_pgsql; \
  docker-php-ext-enable bcmath; \
  pecl install excimer; \
  docker-php-ext-enable excimer; \
  pecl clear-cache; \
  apt-get purge -y --auto-remove autoconf build-essential; \
  rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html