# PHP z FPM
FROM php:8.2-fpm

# Instalacja zależności systemowych
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    unzip \
    libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql

# Skopiuj plik konfiguracyjny Nginx
COPY nginx.conf /etc/nginx/sites-enabled/default

# Ustaw katalog roboczy
WORKDIR /var/www/html

# Skopiuj aplikację do kontenera
COPY . .

# Instalacja zależności Laravel
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

# Uprawnienia
RUN chown -R www-data:www-data /var/www/html

# Eksponuj port HTTP
EXPOSE 80

# Komenda startowa
CMD service nginx start && php-fpm
