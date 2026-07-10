# syntax=docker/dockerfile:1

FROM php:8.4-cli-alpine AS base

# System dependencies + PHP extensions umum buat Laravel
RUN apk add --no-cache \
        bash \
        git \
        curl \
        unzip \
        libpng-dev \
        libzip-dev \
        oniguruma-dev \
        icu-dev \
        libxml2-dev \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy composer files dulu biar layer cache-nya efektif
COPY composer.json composer.lock ./

RUN composer install \
        --no-dev \
        --no-scripts \
        --no-interaction \
        --optimize-autoloader \
        --no-progress

# Copy sisa source code
COPY . .

# Selesaikan autoload/discovery setelah semua file ada
RUN composer dump-autoload --optimize \
    && php artisan package:discover --ansi || true

# Permission untuk storage & cache Laravel
RUN mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

ENV PORT=8080
EXPOSE 8080

# Cache config saat start (bukan saat build, karena .env baru ada di runtime Railway)
CMD php artisan config:cache \
    && php artisan route:cache \
    && php artisan migrate --force \
    && php artisan serve --host=0.0.0.0 --port=${PORT}
