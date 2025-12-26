#!/bin/sh
set -e

echo "Esperando a que MySQL este listo..."
max_tries=30
count=0
until php -r "new PDO('mysql:host=db;dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}');" 2>/dev/null || [ $count -eq $max_tries ]; do
  echo "MySQL no esta listo - esperando... (intento $count/$max_tries)"
  count=$((count + 1))
  sleep 2
done

if [ $count -eq $max_tries ]; then
    echo "Error: No se pudo conectar a MySQL despues de $max_tries intentos"
    exit 1
fi

echo "Base de datos conectada"

echo "Ejecutando migraciones..."
php artisan migrate --force || true

if [ "$APP_ENV" = "production" ]; then
    echo "Optimizando para produccion..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

php artisan storage:link --force 2>/dev/null || true

echo "Iniciando aplicacion..."
exec "$@"