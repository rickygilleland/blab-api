release: ./release-tasks.sh
web: vendor/bin/heroku-php-apache2 -i custom_php.ini public/
worker: php artisan queue:work redis --sleep=3 --tries=3 --daemon