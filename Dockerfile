# ==========================================
# Stage 1: Build PHP dependencies
# ==========================================
FROM composer:2.7 as vendor
WORKDIR /app

# Copy minimum files required to run composer install
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist

# Copy the rest of the application
COPY . .

# Generate optimized autoload files
RUN composer dump-autoload --optimize --classmap-authoritative

# ==========================================
# Stage 2: Build Node dependencies & Vite assets
# ==========================================
FROM node:22-alpine as frontend
WORKDIR /app

# Copy files required to install node modules
COPY package.json package-lock.json* ./
RUN npm install

# Copy application files and vendor folder
COPY . .
COPY --from=vendor /app/vendor /app/vendor

# Build frontend assets
RUN npm run build

# ==========================================
# Stage 3: Production Server (PHP + Apache)
# ==========================================
FROM php:8.3-apache

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libicu-dev \
    libzip-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl zip opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite and enforce prefork (fixes AH00534 multi-mpm error)
RUN a2dismod mpm_event mpm_worker mpm_prefork 2>/dev/null || true \
    && rm -f /etc/apache2/mods-enabled/mpm_*.load \
    && rm -f /etc/apache2/mods-enabled/mpm_*.conf \
    && a2enmod mpm_prefork \
    && a2enmod rewrite

# Setup DocumentRoot to point to Laravel's public directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set working directory
WORKDIR /var/www/html

# Copy built application from previous stages
COPY --from=vendor /app /var/www/html
COPY --from=frontend /app/public/build /var/www/html/public/build

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose default port
EXPOSE 80

# Configure entrypoint to handle Railway's dynamic PORT, cache clear, and migrations
RUN echo '#!/bin/bash\n\
\n\
# Configure Apache to listen on Railway PORT\n\
if [ ! -z "$PORT" ]; then\n\
  sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf\n\
  sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf\n\
fi\n\
\n\
# Fallback .env if empty on Railway\n\
if [ ! -f .env ]; then\n\
  cp .env.example .env\n\
  php artisan key:generate --no-interaction\n\
fi\n\
\n\
# Wait for MySQL to be ready before running DB-dependent commands\n\
if [ ! -z "$DB_HOST" ]; then\n\
  echo "Waiting for MySQL at $DB_HOST:${DB_PORT:-3306}..."\n\
  for i in $(seq 1 30); do\n\
    php -r "new PDO('"'"'mysql:host=$DB_HOST;port=${DB_PORT:-3306};dbname=$DB_DATABASE'"'"', '"'"'$DB_USERNAME'"'"', '"'"'$DB_PASSWORD'"'"');" 2>/dev/null && break\n\
    echo "MySQL not ready, retrying in 2s... ($i/30)"\n\
    sleep 2\n\
  done\n\
fi\n\
\n\
# Clear only file-based caches (no DB needed)\n\
php artisan config:clear\n\
php artisan route:clear\n\
php artisan view:clear\n\
\n\
# Cache config/routes/views (no DB needed)\n\
php artisan config:cache\n\
php artisan event:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
php artisan filament:cache-components\n\
\n\
# Run migrations (DB must be ready by now)\n\
php artisan migrate --force || true\n\
\n\
# Start Apache in the foreground\n\
exec apache2-foreground\n\
' > /usr/local/bin/entrypoint.sh && chmod +x /usr/local/bin/entrypoint.sh

# Run entrypoint
CMD ["/usr/local/bin/entrypoint.sh"]
