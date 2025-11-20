# ---------------------------
# Stage 1: Composer
# ---------------------------
FROM composer:2 AS composer_stage

# ---------------------------
# Stage 2: FrankenPHP
# ---------------------------
FROM dunglas/frankenphp:php8.2.29-bookworm

# Install system libs
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip unzip git \
    && rm -rf /var/lib/apt/lists/*

# Install GD extension
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Copy composer from stage 1
COPY --from=composer_stage /usr/bin/composer /usr/bin/composer

# Copy app
COPY . /app
WORKDIR /app

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Build front-end
RUN npm ci && npm run build

# Laravel cache
RUN php artisan config:cache && \
    php
