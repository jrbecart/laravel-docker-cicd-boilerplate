#!/bin/bash

# run artisan 
cd /var/www/uottawa-template

chown -R www-data:www-data /var/www/uottawa-template/storage /var/www/uottawa-template/bootstrap/cache /var/www/uottawa-template/public
chmod -R 755 /var/www/uottawa-template/storage /var/www/uottawa-template/bootstrap/cache 

php artisan clear-compiled
php artisan cache:clear
php artisan config:clear
php artisan event:clear
php artisan route:clear
php artisan view:clear
# php artisan migrate
php artisan migrate --database=migrate
# php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache

chown -R www-data:www-data /var/www/uottawa-template/storage /var/www/uottawa-template/bootstrap/cache /var/www/uottawa-template/public
chmod -R 755 /var/www/uottawa-template/storage /var/www/uottawa-template/bootstrap/cache 