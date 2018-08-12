# install composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
chmod -R 777 /var/www/storage
chmod -R 777 /var/www/bootstrap/cache
composer install --optimize-autoloader
php artisan config:cache
php artisan route:cache
composer dump-autoload
php artisan migrate
