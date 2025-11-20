# ---------------------------------------
# Stage 1: Build frontend (Node)
# ---------------------------------------
FROM node:18 AS node_stage
WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build

# ---------------------------------------
# Stage 2: Composer dependencies
# ---------------------------------------
FROM composer:2 AS composer_stage
WORKDIR /app

COPY composer.json composer.lock ./

# FIX: ignore ext-gd requirements here
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-req=ext-gd

# ---------------------------------------
# Stage 3: FrankenPHP runtime
# ---------------------------------------
FROM dunglas/frankenphp:php8.2-bookworm

RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    git \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd zip pdo_mysql

WORKDIR /app

COPY . .
COPY --from=node_stage /app/public/build ./public/build
COPY --from=composer_stage /app/vendor ./vendor

RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

EXPOSE 8080
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
