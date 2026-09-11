# ==========================================
# 1. Etapa Node - Compilar Vite
# ==========================================

FROM node:20-alpine AS frontend

WORKDIR /var/www

COPY package*.json ./

RUN npm install

COPY resources ./resources
COPY vite.config.js ./

RUN npm run build


# ==========================================
# 2. Etapa PHP - Laravel
# ==========================================

FROM php:8.2-cli

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        bcmath \
        exif \
        pcntl \
        zip \
    && rm -rf /var/lib/apt/lists/*


# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


# Dependencias PHP
COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction


# Código Laravel
COPY . .


# Assets generados por Vite
COPY --from=frontend /var/www/public/build ./public/build


# Storage
RUN php artisan storage:link || true


# Cache Laravel
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache


# Puerto de Render
EXPOSE 10000


# Servidor Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]