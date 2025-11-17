FROM php:8.2-apache

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Copiar archivos del proyecto al contenedor
COPY . /var/www/html/

# Ajustar permisos (opcional)
RUN chown -R www-data:www-data /var/www/html

# Puerto que expondrá Apache
EXPOSE 80
