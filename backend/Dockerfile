# Użyj oficjalnego obrazu PHP z rozszerzeniami potrzebnymi dla Laravel
FROM php:8.1-fpm

# Zainstaluj wymagane pakiety
RUN apt-get update && apt-get install -y \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalacja kompozytora (Composer)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Ustaw katalog roboczy
WORKDIR /var/www

# Skopiuj pliki aplikacji do kontenera
COPY . .

# Instalacja zależności aplikacji Laravel
RUN composer install --no-dev --optimize-autoloader

# Ustawienia uprawnień
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Domyślne polecenie startowe
CMD php artisan serve --host=0.0.0.0 --port=80

# Otwórz port 80
EXPOSE 80
