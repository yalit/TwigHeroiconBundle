FROM php:8.2-apache

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt update -yqq
RUN apt upgrade -yqq
RUN apt install -yqq  libjpeg-dev libpng-dev libbz2-dev libxslt-dev libsodium-dev libzip-dev libxslt-dev wget openssh-client zip firefox-esr \
    && docker-php-ext-install zip \
    && docker-php-ext-enable zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
