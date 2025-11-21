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
FROM php:8.2-cli AS composer_stage
WORKDIR /app

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install zip extension (required by composer)
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

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