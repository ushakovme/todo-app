FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
        curl \
        wget \
        git \
        libfreetype6-dev \
        libonig-dev \
        libzip-dev zip \
        supervisor \
    && docker-php-ext-install -j$(nproc) iconv mbstring pdo_mysql zip pcntl\
    && docker-php-source delete

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN usermod -u 1000 www-data
RUN sleep 5

ADD docker/php/php.ini /usr/local/etc/php/conf.d/40-custom.ini

COPY . /var/www
RUN chown -R www-data:www-data /var/www

WORKDIR /var/www

RUN composer install --no-dev

CMD ["php-fpm"]
