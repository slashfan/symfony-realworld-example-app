FROM php:7.4-fpm

# Set TIMEZONE
RUN rm /etc/localtime \
    && ln -s /usr/share/zoneinfo/Europe/Paris /etc/localtime \
    && "date"

# Install TOOLS
RUN apt-get update \
    && apt-get install -y git curl wget unzip \
    && rm -rf /var/lib/apt/lists/*

# Install OPCACHE extension
RUN docker-php-ext-install opcache

# Install APCU extension
RUN pecl install apcu \
    && docker-php-ext-enable apcu

# Install INTL extension
RUN apt-get update \
    && apt-get install -y libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && rm -rf /var/lib/apt/lists/*

# Install ZIP extension
RUN apt-get update \
    && apt-get install -y libzip-dev zip \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

# Install PDO MYSQL extension
RUN docker-php-ext-install pdo_mysql

# Install PCOV extension
RUN pecl install pcov \
    && docker-php-ext-enable pcov

# Install COMPOSER
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Set PROJECT USER
RUN useradd -ms /bin/bash project
USER project
WORKDIR /project

COPY conf/php-fpm.conf /etc/php-fpm.conf
COPY conf/php.ini /usr/local/etc/php/conf.d/100-php.ini
