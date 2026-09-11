FROM php:8.2-cli

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
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

# Node.js y npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get update \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Dependencias PHP
COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-scripts

# Dependencias frontend
COPY package.json package-lock.json ./

RUN npm ci

# Código de Laravel
COPY . .

# Ahora que está todo el código, generamos el autoload
RUN composer dump-autoload --optimize --no-interaction

# Build de Vite
RUN npm run build

EXPOSE 10000

CMD ["sh", "-c", "php artisan package:discover --ansi && php artisan storage:link || true; php artisan serve --host=0.0.0.0 --port=10000"]