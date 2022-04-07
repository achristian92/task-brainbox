web: vendor/bin/heroku-php-apache2 public/
worker: php artisan queue:restart && php artisan queue:work --tries=3 --delay=3 --daemon
scheduler: php -d memory_limit=512M artisan schedule:daemon
