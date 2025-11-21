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
FROM composer:2.8 AS composer_stage
WORKDIR /app

# Install PHP 8.2 dan extensions yang diperlukan
RUN apk add --no-cache php82 php82-zip php82-gd php82-pdo php82-pdo_mysql

COPY composer.json composer.lock ./

# Gunakan PHP 8.2 untuk menjalankan composer
RUN php82 /usr/bin/composer install \
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

ENV LOG_CHANNEL=stderr
ENV LOG_LEVEL=debug

# copy full project
COPY . .

# copy node build
COPY --from=node_stage /app/public/build ./public/build

# copy vendor from composer_stage
COPY --from=composer_stage /app/vendor ./vendor


EXPOSE 8080

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]