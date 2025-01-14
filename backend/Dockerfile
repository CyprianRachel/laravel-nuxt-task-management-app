# Wybierz obraz bazowy z PHP 8.2
FROM php:8.2-fpm

# Instalacja zależności systemowych i rozszerzeń PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql

# Ustaw katalog roboczy
WORKDIR /var/www

# Skopiuj pliki projektu do obrazu
COPY . .

# Instalacja zależności Laravel za pomocą Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

# Ustawienia uprawnień
RUN chown -R www-data:www-data /var/www

# Eksponuj port
EXPOSE 9000

# Komenda startowa
CMD ["php-fpm"]
