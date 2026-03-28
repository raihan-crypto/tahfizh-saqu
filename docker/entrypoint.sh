#!/bin/bash
set -e

# Configure Apache to listen on Railway PORT
if [ ! -z "$PORT" ]; then
  sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
  sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf
fi

# Remove conflicting MPM modules (mpm_event must not coexist with mpm_prefork)
rm -f /etc/apache2/mods-enabled/mpm_event.load \
      /etc/apache2/mods-enabled/mpm_event.conf \
      /etc/apache2/mods-enabled/mpm_worker.load \
      /etc/apache2/mods-enabled/mpm_worker.conf

# Fallback .env if not present
if [ ! -f .env ]; then
  cp .env.example .env
  php artisan key:generate --no-interaction
fi

# Wait for MySQL to be ready
if [ ! -z "$DB_HOST" ]; then
  echo "Waiting for MySQL at ${DB_HOST}:${DB_PORT:-3306}..."
  for i in $(seq 1 30); do
    if php -r "
      try {
        \$pdo = new PDO('mysql:host=${DB_HOST};port=${DB_PORT:-3306};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}');
        exit(0);
      } catch (Exception \$e) {
        exit(1);
      }
    " 2>/dev/null; then
      echo "MySQL is ready."
      break
    fi
    echo "MySQL not ready, retrying in 2s... (${i}/30)"
    sleep 2
  done
fi

# Clear file-based caches only
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cache everything
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache
php artisan filament:cache-components

# Run migrations
php artisan migrate --force || true

# Start Apache
exec apache2-foreground
