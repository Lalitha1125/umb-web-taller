# Usar PHP con Apache
FROM php:8.2-apache

# Instalar extensiones necesarias para PDO_PGSQL
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo_pgsql

# Copiar API a la raíz pública de Apache
COPY api/ /var/www/html/

# Permisos (opcional)
RUN chown -R www-data:www-data /var/www/html

# Habilitar mod_rewrite
RUN a2enmod rewrite

EXPOSE 80
CMD ["apache2-foreground"]
