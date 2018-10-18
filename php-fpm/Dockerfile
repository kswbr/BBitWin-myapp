FROM php:7.2-fpm-alpine

USER root

COPY php-fpm/laravel.ini /usr/local/etc/php/conf.d
COPY php-fpm/xlaravel.pool.conf /usr/local/etc/php-fpm.d/

USER root

WORKDIR /var/www
COPY dist/src /var/www

RUN rm -rf /var/www/bootstrap/cache/*
RUN rm -rf /var/www/storage
RUN rm -rf /var/www/public
RUN rm -f /var/www/config/passport.php
COPY storage /var/www/storage
COPY dist/src/public/index.php /var/www/public/index.php
COPY dist/src/public/mix-manifest.json /var/www/public/mix-manifest.json

RUN chmod -R 777 /var/www/storage
RUN chmod -R 777 /var/www/bootstrap/cache

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev
RUN composer dump-autoload

RUN php artisan cache:clear
RUN php artisan config:clear
RUN php artisan passport:keys

CMD ["php-fpm"]

EXPOSE 9000
