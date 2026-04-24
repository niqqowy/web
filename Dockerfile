FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev \
    && docker-php-ext-install zip sockets

WORKDIR /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY composer.json .


RUN composer install --no-interaction --prefer-dist

COPY www/index.php .
COPY www/send.php .
COPY www/worker.php .
COPY www/QueueManager.php .

RUN chmod -R 777 /var/www/html

CMD ["php-fpm"]