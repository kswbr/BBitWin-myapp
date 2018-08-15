#
#--------------------------------------------------------------------------
# Image Setup
#--------------------------------------------------------------------------
#
# To edit the 'php-fpm' base Image, visit its repository on Github
#    https://github.com/Laradock/php-fpm
#
# To change its version, see the available Tags on the Docker Hub:
#    https://hub.docker.com/r/laradock/php-fpm/tags/
#
# Note: Base Image name format {image-tag}-{php-version}
#

ARG LARADOCK_PHP_VERSION=7.2

FROM laradock/php-fpm:2.2-${LARADOCK_PHP_VERSION}

LABEL maintainer="Bugwabara"

ARG LARADOCK_PHP_VERSION=7.2

# Set Environment Variables
ENV DEBIAN_FRONTEND noninteractive
ENV APP_ENV production

# always run apt update when start and after add new source list, then clean up at end.
RUN apt-get update -yqq && \
    apt-get install -y apt-utils && \
    pecl channel-update pecl.php.net

RUN apt-get install curl gnupg cmake -y &&\
    mkdir /tmp/libzip && cd /tmp/libzip && curl -sSLO https://libzip.org/download/libzip-1.4.0.tar.gz &&\
    tar zxf libzip-1.4.0.tar.gz && cd libzip-1.4.0/ && mkdir build && cd build && cmake ../ &&\
    make > /dev/null && make install &&\
    docker-php-ext-configure zip --with-libzip=/usr/local/lib/libzip.so &&\
    docker-php-ext-install zip

#
#--------------------------------------------------------------------------
# Mandatory Software's Installation
#--------------------------------------------------------------------------
#
# Mandatory Software's such as ("mcrypt", "pdo_mysql", "libssl-dev", ....)
# are installed on the base image 'laradock/php-fpm' image. If you want
# to add more Software's or remove existing one, you need to edit the
# base image (https://github.com/Laradock/php-fpm).
#

#
#--------------------------------------------------------------------------
# Optional Software's Installation
#--------------------------------------------------------------------------
#
# Optional Software's will only be installed if you set them to `true`
# in the `docker-compose.yml` before the build.
# Example:
#   - INSTALL_ZIP_ARCHIVE=true
#

###########################################################################
# SOAP:
###########################################################################

ARG INSTALL_SOAP=true

RUN if [ ${INSTALL_SOAP} = true ]; then \
    # Install the soap extension
    rm /etc/apt/preferences.d/no-debian-php && \
    apt-get -y install libxml2-dev php-soap && \
    docker-php-ext-install soap \
;fi

###########################################################################
# Image optimizers:
###########################################################################

USER root

ARG INSTALL_IMAGE_OPTIMIZERS=false

RUN if [ ${INSTALL_IMAGE_OPTIMIZERS} = true ]; then \
    apt-get install -y jpegoptim optipng pngquant gifsicle \
;fi

###########################################################################
# ImageMagick:
###########################################################################

USER root

ARG INSTALL_IMAGEMAGICK=false

RUN if [ ${INSTALL_IMAGEMAGICK} = true ]; then \
    apt-get install -y libmagickwand-dev imagemagick && \
    pecl install imagick && \
    docker-php-ext-enable imagick \
;fi

###########################################################################
# Check PHP version:
###########################################################################

RUN php -v | head -n 1 | grep -q "PHP ${LARADOCK_PHP_VERSION}."

#
#--------------------------------------------------------------------------
# Final Touch
#--------------------------------------------------------------------------
#

COPY php-fpm/laravel.ini /usr/local/etc/php/conf.d
COPY php-fpm/xlaravel.pool.conf /usr/local/etc/php-fpm.d/

USER root

# Clean up
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    rm /var/log/lastlog /var/log/faillog

RUN usermod -u 1000 www-data

WORKDIR /var/www
COPY dist/src /var/www

# install composer
RUN rm -rf /var/www/bootstrap/cache/*
RUN rm -rf /var/www/storage
RUN rm -f /var/www/config/passport.php
COPY storage /var/www/storage

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
