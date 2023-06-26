cp .env.example .env
- edit .env

composer install

php artisan migrate:fresh --seed
php artisan queue:work
