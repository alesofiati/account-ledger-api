# syntax=docker/dockerfile:1

FROM php:8.4-fpm-alpine AS base

RUN apk add --no-cache \
    icu-dev \
    oniguruma-dev \
    libzip-dev \
    sqlite-dev \
    unzip \
    git \
    $PHPIZE_DEPS \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        intl \
        mbstring \
        opcache \
        pdo \
        pdo_sqlite \
        zip \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del $PHPIZE_DEPS

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

FROM base AS vendor

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader \
    --classmap-authoritative

FROM base AS app

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY docker/php/99-production.ini /usr/local/etc/php/conf.d/99-production.ini
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

COPY --from=vendor /var/www/html/vendor /var/www/html/vendor
COPY . .

RUN rm -f bootstrap/cache/packages.php bootstrap/cache/services.php \
    && composer dump-autoload --no-dev --classmap-authoritative --no-interaction \
    && php artisan package:discover --ansi \
    && mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

USER www-data

EXPOSE 9000

CMD ["php-fpm", "-F"]

