FROM php:7.2-fpm

RUN apt-get update \
    && apt-get install -y git curl wget unzip

# Set TIMEZONE
RUN rm /etc/localtime \
    && ln -s /usr/share/zoneinfo/Europe/Paris /etc/localtime \
    && "date"

# Install OPCACHE extension
RUN docker-php-ext-install opcache

# Install INTL extension
RUN apt-get install -y libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

# Install APCU extension
RUN pecl install apcu \
    && docker-php-ext-enable apcu

# Install PDO MYSQL extension
RUN docker-php-ext-install pdo_mysql

# Install ZIP extension
RUN apt-get install -y libzip-dev zip \
  && docker-php-ext-configure zip --with-libzip \
  && docker-php-ext-install zip

# Install COMPOSER
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install NODEJS
RUN apt-get install build-essential -y \
    && curl -L -o /tmp/nodejs.tar.gz https://nodejs.org/dist/v10.14.2/node-v10.14.2-linux-x64.tar.gz \
    && tar xfvz /tmp/nodejs.tar.gz -C /usr/local --strip-components=1 \
    && rm -f /tmp/nodejs.tar.gz

# Set PROJECT USER
RUN useradd -ms /bin/bash project
USER project
WORKDIR /project

COPY conf/php-fpm.conf /etc/php-fpm.conf
COPY conf/php.ini /usr/local/etc/php/conf.d/100-php.ini
