# Imagen base de PHP 8.2 con Apache
FROM php:8.2-apache

# Instalar dependencias del sistema
# En el paso 2, cambia libpq-dev por default-libmysqlclient-dev
# Y pdo_pgsql por pdo_mysql

RUN apt-get update && apt-get install -y \
    default-libmysqlclient-dev \
    libzip-dev \
    unzip \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_mysql bcmath zip

# 3. Configurar Apache para que la raíz sea /public (Estándar de Laravel)
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 4. Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# 5. Copiar los archivos del proyecto al contenedor
COPY . /var/www/html

# 6. Instalar dependencias de PHP (Composer)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 7. Instalar dependencias de Frontend (NPM) y construir recursos (Vite)
RUN npm install
RUN npm run build

# 8. Permisos correctos para carpetas de almacenamiento
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 9. Puerto que expondrá el contenedor (Render usa la variable PORT, pero por defecto 80)
EXPOSE 80

# 10. Comando de inicio: Migrar la BD y luego iniciar Apache
CMD php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan migrate --force && \
    apache2-foreground