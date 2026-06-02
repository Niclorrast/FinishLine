FROM php:8.2-fpm

# Extensiones de PHP necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Instalar git y unzip
RUN apt-get update && apt-get install -y git unzip && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalar PHPMailer en /var/composer (FUERA del volumen, así no se sobreescribe)
COPY composer.json /var/composer/composer.json
RUN composer install --no-interaction --no-dev --optimize-autoloader --prefer-dist \
    --working-dir=/var/composer

COPY php/php.ini /usr/local/etc/php/conf.d/uploads.ini

WORKDIR /var/www/html

# Instalar y habilitar Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug