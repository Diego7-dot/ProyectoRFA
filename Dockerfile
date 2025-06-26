# Imagen base: PHP 8.2 con Apache
FROM php:8.2-apache

# Instalar extensiones necesarias para MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copiar todos los archivos del proyecto al directorio de Apache
COPY . /var/www/html/

# Activar el módulo rewrite de Apache (para URLs amigables)
RUN a2enmod rewrite

# Exponer el puerto 80 (usado por Apache y Railway)
EXPOSE 80