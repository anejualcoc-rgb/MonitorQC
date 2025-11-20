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

# Copy full Laravel project
COPY . .

# Copy built frontend assets
COPY --from=node_stage /app/public/build ./public/build

# Copy vendor from composer stage
COPY --from=composer_stage /app/vendor ./vendor

# Clear caches to avoid cached errors
RUN php artisan config:clear && \
    php artisan route:clear && \
    php artisan view:clear

# Rebuild cache
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Expose port
EXPOSE 8080

# Run Laravel via FrankenPHP (NOT artisan serve)
CMD ["frankenphp", "run", "--config=frankenphp.json"]
