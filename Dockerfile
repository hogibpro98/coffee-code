FROM php:8.0-apache

ARG ENV
ENV ENV $ENV

COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY ./docker/apache/*.conf /etc/apache2/sites-enabled/
COPY ./docker/apache/php.ini-production /usr/local/etc/php/php.ini

RUN apt-get update \
  && apt-get install -y libzip-dev zlib1g-dev libpq-dev mariadb-client unzip\
  && docker-php-ext-install zip pdo_mysql mysqli \
  && docker-php-ext-enable mysqli

RUN apt-get install -y wget libjpeg-dev libfreetype6-dev
RUN apt-get install -y  libmagick++-dev \
  libmagickwand-dev \
  libpq-dev \
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  libpng-dev \
  libwebp-dev \
  libssl-dev \
  openssl \
  ssl-cert \
  libxpm-dev

RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install -j$(nproc) gd

RUN apt-get install -y git

RUN /usr/sbin/a2enmod rewrite.load
RUN /usr/sbin/a2enmod headers.load

WORKDIR /var/www/html
COPY . /var/www/html
RUN cp /var/www/html/.env.${ENV} /var/www/html/.env

RUN apt-get install -y cron

RUN wget https://busybox.net/downloads/binaries/1.28.1-defconfig-multiarch/busybox-x86_64
RUN mv busybox-x86_64 busybox
RUN chmod +x busybox
RUN mv busybox /usr/bin

RUN composer install
RUN chmod 777 -R /var/www/html/storage

RUN /bin/sh -c a2enmod rewrite
