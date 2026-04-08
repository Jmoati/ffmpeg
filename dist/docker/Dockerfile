FROM composer:latest AS composer

FROM php:cli AS php

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y ffmpeg && \
    docker-php-ext-install -j$(nproc) pcntl

COPY --from=composer /usr/bin/composer /usr/bin/composer
WORKDIR /app
