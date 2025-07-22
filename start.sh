sh
#!/bin/sh
php artisan migrate --force
nginx &
php-fpm
wait -n
