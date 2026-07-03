FROM php:8.5-fpm

# Установка системных зависимостей
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd intl zip \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Установка Composer из официального образа
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Копируем файлы проекта из локальной папки в контейнер
COPY . .

RUN composer install --no-interaction --optimize-autoloader --no-dev

# Настройка прав (важно для Laravel, чтобы он мог писать в storage)
RUN chown -R www-data:www-data storage/ bootstrap/

# PHP-FPM запускается автоматически родительским образом
EXPOSE 9000
CMD ["php-fpm"]