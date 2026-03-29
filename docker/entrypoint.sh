#!/bin/bash
set -e

# Fix Apache MPM conflict
a2dismod mpm_event mpm_worker 2>/dev/null || true
a2enmod mpm_prefork 2>/dev/null || true

# Configure Apache port for Railway
APACHE_PORT=${PORT:-80}
export APACHE_PORT=$(echo "$APACHE_PORT" | tr -d '\r\n\t ')
echo "Listen ${APACHE_PORT}" > /etc/apache2/ports.conf
sed -i "s/*:80/*:${APACHE_PORT}/g" /etc/apache2/sites-available/000-default.conf

# Fallback .env if not present
if [ ! -f /var/www/html/.env ]; then
  cp /var/www/html/.env.example /var/www/html/.env
  php artisan key:generate --no-interaction
fi

# Force safe drivers as fallback
export SESSION_DRIVER=${SESSION_DRIVER:-file}
export CACHE_STORE=${CACHE_STORE:-file}
export QUEUE_CONNECTION=${QUEUE_CONNECTION:-sync}

# Ensure storage dirs are writable
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Wait for MySQL
DB_READY=false
if [ ! -z "$DB_HOST" ]; then
  echo "Waiting for MySQL at ${DB_HOST}:${DB_PORT:-3306}..."
  for i in $(seq 1 30); do
    if php -r "
      try {
        new PDO('mysql:host=${DB_HOST};port=${DB_PORT:-3306};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}');
        exit(0);
      } catch (Exception \$e) {
        exit(1);
      }
    " 2>/dev/null; then
      echo "MySQL is ready."
      DB_READY=true
      break
    fi
    echo "MySQL not ready, retrying in 2s... (${i}/30)"
    sleep 2
  done

  if [ "$DB_READY" = false ]; then
    echo "WARNING: MySQL not ready. Falling back to file session/cache."
    export SESSION_DRIVER=file
    export CACHE_STORE=file
  fi
fi

cd /var/www/html

# Sanitize env vars that might have CR/LF from copy-paste
export APP_URL=$(echo "$APP_URL" | tr -d '\r\n\t')
export DB_HOST=$(echo "$DB_HOST" | tr -d '\r\n\t')
export DB_PASSWORD=$(echo "$DB_PASSWORD" | tr -d '\r\n\t')
export PORT=$(echo "$PORT" | tr -d '\r\n\t ')

php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache
php artisan filament:cache-components

if [ "$DB_READY" = true ]; then
  php artisan migrate --force
  php artisan storage:link --force 2>/dev/null || true
fi

# Tail log to stdout
mkdir -p /var/www/html/storage/logs
touch /var/www/html/storage/logs/laravel.log
chown www-data:www-data /var/www/html/storage/logs/laravel.log
tail -f /var/www/html/storage/logs/laravel.log &

exec apache2-foreground
