FROM composer:latest as build
COPY . /app/
RUN composer install --prefer-dist --no-dev --optimize-autoloader --no-interaction

FROM php:8.1-apache-buster as production

ENV APP_ENV=production
ENV APP_DEBUG=false

RUN docker-php-ext-configure opcache --enable-opcache && \
    docker-php-ext-install pdo pdo_mysql
COPY docker/php/conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

COPY --from=build /app /var/www/html
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY .env.example /var/www/html/.env

RUN php artisan key:generate
#RUN php artisan config:cache
#RUN php artisan route:cache
RUN chmod 777 -R /var/www/html/storage/
RUN chown -R www-data:www-data /var/www/
RUN a2enmod rewrite
