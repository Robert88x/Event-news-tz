# Stage 1: Build frontend assets
FROM node:20 AS node_builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# Stage 2: Setup PHP and Apache
FROM php:8.2-apache
WORKDIR /var/www/html

# 1. Add libzip-dev to the system dependencies list
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    **libzip-dev** \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Add 'zip' to the PHP extensions list
RUN docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd zip

# Copy Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Update Apache's DocumentRoot
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 3. Copy files BEFORE composer install
COPY . /var/www/html
COPY --from=node_builder /app/public/build /var/www/html/public/build

# 4. Set permissions BEFORE composer install (sometimes fixes folder creation errors)
RUN chown -R www-data:www-data /var/www/html

# 5. Run Composer as the root user (default) to ensure it can write to vendor
RUN composer install --optimize-autoloader --no-interaction --no-dev --ignore-platform-reqs

# Final permissions check
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]