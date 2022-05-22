#FROM php

#RUN docker-php-ext-install pdo_mysql mbstring ctype iconv
#RUN docker-php-ext-install mysqli pdo_mysql
#CMD ["php", "-S", "0.0.0.0:8001", "-t", "public"]

FROM php:7.4-fpm

RUN apt-get update && apt-get install -y libmcrypt-dev
   # mysql-client libmagickwand-dev --no-install-recommends
# Install Composer

# 1. development packages
RUN apt-get install -y \
    git \
    zip \
    curl \
    sudo \
    unzip \
    libicu-dev \
    libbz2-dev \
    libpng-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libreadline-dev \
    libfreetype6-dev \
    g++

# 2. install other packages
RUN apt-get update && \
    apt-get install -y autoconf pkg-config libssl-dev git libzip-dev zlib1g-dev && \
    pecl install mongodb && docker-php-ext-enable mongodb && \
    pecl install xdebug && docker-php-ext-enable xdebug && \
    docker-php-ext-install -j$(nproc) pdo_mysql zip

RUN apt-get install -y zlib1g-dev libzip-dev unzip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#CMD ["php", "-S", "0.0.0.0:8001", "-t", "public"]
#https://34.203.28.18/
CMD ["php", "-S", "34.203.28.18:80", "-t", "public"]