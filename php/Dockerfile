FROM php:7.4-fpm


RUN apt update && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip  build-essential libprotobuf-dev libboost-dev openssl protobuf-compiler liblz4-tool zstd\
    && docker-php-ext-install intl opcache pdo pdo_mysql \
    && pecl install apcu \
    && pecl install mysql_xdevapi \
    && docker-php-ext-enable apcu \
    && docker-php-ext-enable mysql_xdevapi \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip



WORKDIR /var/www/symfony_docker

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -1sLf https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh | bash
RUN apt install symfony-cli
