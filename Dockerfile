# Usar imagen base de PHP con Apache
FROM php:8.2-apache

# Actualizar repositorios e instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Copiar todos los archivos de la carpeta 'api' al DocumentRoot de Apache
COPY api/ /var/www/html/

# Ajustar permisos para que Apache pueda leer y escribir si es necesario
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Exponer puerto 80 para el contenedor
EXPOSE 80

# Comando por defecto para arrancar Apache en primer plano
CMD ["apache2-foreground"]
