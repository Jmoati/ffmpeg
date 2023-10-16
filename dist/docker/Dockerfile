FROM composer:latest AS composer

FROM php:fpm as php

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y ffmpeg && \
    apt-get install -y libicu-dev  libzip-dev unzip && \
    docker-php-ext-install -j$(nproc) bcmath exif intl opcache pcntl sockets zip calendar


COPY --from=composer /usr/bin/composer /usr/bin/composer
WORKDIR /app
