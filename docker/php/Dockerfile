FROM php:7.1-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    wget \
    curl

# Set TIMEZONE
RUN rm /etc/localtime \
    && ln -s /usr/share/zoneinfo/Europe/Paris /etc/localtime \
    && "date"

# Install APCU extension
RUN pecl install apcu \
    && echo "extension=apcu.so" > /usr/local/etc/php/conf.d/apcu.ini

# Install OPCACHE extension
RUN docker-php-ext-install opcache

# Install GD extension
RUN apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng12-dev \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd

# Install MBSTRING extension
RUN docker-php-ext-install mbstring

# Install MCRYPT extension
RUN apt-get install -y libmcrypt-dev \
    && docker-php-ext-install mcrypt

# Install PDO extension
RUN apt-get install -y libsqlite3-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite

# Install INTL extension
RUN apt-get install -y libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

# Install BCMATH extension
RUN docker-php-ext-install bcmath

# Install ZIP extension
RUN docker-php-ext-install zip

# Install COMPOSER
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer --version

# Set SYMFONY USER
RUN useradd -ms /bin/bash symfony
USER symfony
WORKDIR /symfony

COPY conf/php-fpm.conf /etc/php-fpm.conf
COPY conf/php.ini /usr/local/etc/php/conf.d/100-php.ini
