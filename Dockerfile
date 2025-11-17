# Usar imagen oficial de PHP con Apache
FROM php:8.2-apache

# Habilitar módulos necesarios
RUN a2enmod rewrite
RUN docker-php-ext-install pdo pdo_pgsql

# Copiar los archivos de la API al directorio web
COPY api/ /var/www/html/

# Ajustar permisos
RUN chown -R www-data:www-data /var/www/html

# Exponer el puerto 80
EXPOSE 80

# Comando para iniciar Apache en primer plano
CMD ["apache2-foreground"]
