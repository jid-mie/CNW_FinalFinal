FROM php:8.3-fpm-alpine

WORKDIR /var/www

RUN apk add --no-cache postgresql-dev

RUN docker-php-ext-install pdo_pgsql pgsql mbstring

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-interaction --optimize-autoloader --no-dev

RUN npm install --ignore-scripts && npm run build

RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

COPY .docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
