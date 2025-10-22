FROM php:8.2-apache

# install dependencies for AWS SDK
RUN apt-get update && apt-get install -y unzip libzip-dev \
    && docker-php-ext-install zip

COPY . /var/www/html/
WORKDIR /var/www/html/

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

EXPOSE 80
CMD ["apache2-foreground"]
