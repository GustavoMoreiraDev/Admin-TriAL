FROM php:8.3-fpm-alpine

WORKDIR /var/www

RUN apk add --no-cache \
    postgresql-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    oniguruma-dev

RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    pgsql \
    zip \
    mbstring \
    bcmath \
    pcntl

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

COPY composer.json ./

RUN composer install --no-scripts --no-autoloader --no-interaction --prefer-dist

COPY . .

RUN composer dump-autoload --optimize \
    && php artisan storage:link --quiet || true \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]
