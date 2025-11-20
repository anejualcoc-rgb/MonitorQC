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

RUN composer install \
    --no-dev \
    --no-scripts \
    --optimize-autoloader \
    --no-interaction \
    --ignore-platform-req=ext-gd


# ---------------------------------------
# Stage 3: Runtime (FrankenPHP)
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

# ---------------------------------------
# 🔥 Tambahkan ini di bawah WORKDIR /app
# ---------------------------------------
ENV LOG_CHANNEL=stderr
ENV LOG_LEVEL=debug
# ---------------------------------------

# copy full project
COPY . .

# copy node build
COPY --from=node_stage /app/public/build ./public/build

# copy vendor from composer_stage
COPY --from=composer_stage /app/vendor ./vendor

RUN echo "APP_KEY=${APP_KEY}" >> .env && \
    echo "DB_CONNECTION=${DB_CONNECTION}" >> .env && \
    echo "DB_HOST=${DB_HOST}" >> .env && \
    echo "DB_PORT=${DB_PORT}" >> .env && \
    echo "DB_DATABASE=${DB_DATABASE}" >> .env && \
    echo "DB_USERNAME=${DB_USERNAME}" >> .env && \
    echo "DB_PASSWORD=${DB_PASSWORD}" >> .env

# laravel cache
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

EXPOSE 8080

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
