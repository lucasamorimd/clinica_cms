FROM phpdockerio/php80-fpm

WORKDIR /application

COPY . /application/

RUN apt-get update \
    && apt-get install -y zip unzip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-interaction

RUN apt-get update \
    && apt-get -y --no-install-recommends install \
        php8.0-mysql \ 
        php8.0-pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN chmod -R 777 /application

RUN php artisan key:generate

EXPOSE 9000