FROM richarvey/nginx-php-fpm:latest

ENV WEBROOT=/var/www/html/public

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chmod -R 775 storage bootstrap/cache

EXPOSE 80