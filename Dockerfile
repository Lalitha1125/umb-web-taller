# Dockerfile para backend PHP + Neon/Postgres en Render

# 1️⃣ Usar la imagen oficial de PHP con Apache
FROM php:8.2-apache

# 2️⃣ Instalar extensiones necesarias para PostgreSQL
RUN apt-get update && \
    apt-get install -y libpq-dev && \
    docker-php-ext-install pdo_pgsql

# 3️⃣ Copiar la carpeta api/ al directorio web de Apache
COPY api/ /var/www/html/

# 4️⃣ Habilitar mod_rewrite para URLs amigables (opcional pero recomendado)
RUN a2enmod rewrite

# 5️⃣ Permisos (opcional)
RUN chown -R www-data:www-data /var/www/html/ && \
    chmod -R 755 /var/www/html/

# 6️⃣ Puerto expuesto (Render lo usa internamente)
EXPOSE 10000

# 7️⃣ Comando por defecto (ya viene con Apache)
CMD ["apache2-foreground"]
