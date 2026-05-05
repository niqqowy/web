FROM php:8.1-cli

RUN apt-get update && \
    apt-get install -y \
        zip \
        unzip \
        git && \
    rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . /var/www/html

CMD ["php", "-a"]