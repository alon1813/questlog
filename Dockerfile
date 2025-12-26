FROM php:8.2-apache

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    default-libmysqlclient-dev \
    libzip-dev \
    unzip \
    curl \
    git \
    && docker-php-ext-install pdo pdo_mysql bcmath zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configurar Apache
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf \
    && a2enmod rewrite

WORKDIR /var/www/html

# Copiar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copiar archivos
COPY . .

# Instalar dependencias
RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && npm ci \
    && npm run build

# Permisos
RUN mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Copiar y dar permisos al script
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh \
    && dos2unix /usr/local/bin/docker-entrypoint.sh 2>/dev/null || sed -i 's/\r$//' /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]