#!/bin/sh

set -e
composer install --prefer-dist --optimize-autoloader
chown -R www-data:www-data /var/www/storage \
 && chown -R www-data:www-data /var/www/bootstrap \
 && php /var/www/artisan key:generate \
 && php /var/www/artisan config:cache \
 && php /var/www/artisan route:cache
 # && php /var/www/artisan migrate --force --seed

php-fpm
