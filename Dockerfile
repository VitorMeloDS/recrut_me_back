FROM php:8.2-fpm

# Instala extensões e dependências
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho
WORKDIR /var/www

# Copia o projeto
COPY . .

# Instala dependências do Laravel
RUN composer install --no-dev --optimize-autoloader

# Dá permissão para storage e bootstrap
RUN chmod -R 777 storage bootstrap/cache

CMD php artisan serve --host=0.0.0.0 --port=8000

EXPOSE 8000
