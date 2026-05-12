FROM php:8.5-fpm
RUN docker-php-ext-install pdo pdo_mysql
RUN apt-get update && apt-get install -y \
    zip unzip git \
    && rm -rf /var/lib/apt/lists/*
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html

COPY composer.json ./
COPY phpunit.xml ./
COPY ./code /var/www/html
COPY ./tests /var/www/html/tests
CMD ["php-fpm"]