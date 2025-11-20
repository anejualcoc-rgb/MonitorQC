FROM dunglas/frankenphp:php8.2.29-bookworm

# Install system libs
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip unzip git \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Copy Laravel files
COPY . /app
WORKDIR /app

# Install composer dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Build assets
RUN npm ci && npm run build

# Laravel cache
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Start server
CMD ["frankenphp", "run", "--config", "/app/Caddyfile"]
