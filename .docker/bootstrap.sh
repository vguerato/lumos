#!/usr/bin/env sh
#!/bin/sh
set -eu

# Make files fully writable/readable/executable by everyone to prevent read-only issues
umask 000
chmod -R 777 /var/www/html || true
chown -R www-data:www-data /var/www/html || true

cd /var/www/html

# Garantir estrutura de logs
mkdir -p storage/logs
: > storage/logs/laravel.log

# Criar .env se não existir
if [ ! -f .env ] && [ -f .env.example ]; then
  cp .env.example .env
fi

# Instalar dependências do Composer se vendor/ estiver ausente
if [ ! -d vendor ]; then
  composer install --no-interaction --prefer-dist --no-progress
fi

# Gerar APP_KEY se ausente
if ! grep -q '^APP_KEY=base64:' .env; then
  php artisan key:generate --force
fi

php artisan optimize
php artisan migrate --force
exec php-fpm -F

