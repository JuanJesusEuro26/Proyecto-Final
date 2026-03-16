FROM php:7.1-fpm

# Configuración de repositorios antiguos de Debian 
RUN sed -i 's|deb.debian.org|archive.debian.org|g' /etc/apt/sources.list \
 && sed -i '/security.debian.org/d' /etc/apt/sources.list \
 && echo 'Acquire::Check-Valid-Until "false";' > /etc/apt/apt.conf.d/99disable-check-valid-until

RUN apt-get update && apt-get install -y \
    nginx \
    libicu-dev \
    libpng-dev \
    libxml2-dev \
    git \
    unzip \
    libzip-dev

# Instalación de extensiones necesarias para Symfony 2.x
RUN docker-php-ext-install pdo_mysql zip intl mbstring soap gd

WORKDIR /var/www/html

# Configuración de Nginx
COPY ./docker/nginx/default.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Copiamos el proyecto
COPY . .

# Instalamos Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Script de inicio
COPY ./docker/scripts/startup.sh /usr/local/bin/startup.sh
RUN chmod +x /usr/local/bin/startup.sh

# Exponemos el puerto 80 (Nginx)
EXPOSE 80

ENTRYPOINT ["/usr/local/bin/startup.sh"]