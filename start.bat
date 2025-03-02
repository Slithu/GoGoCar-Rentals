%systemDrive%\xampp\mysql\bin\mysql -uroot -e "CREATE DATABASE IF NOT EXISTS gogocar;"
php -r "copy('.env.example', '.env');"
call composer install
call npm install
call php artisan key:generate
call php artisan config:cache
call php artisan storage:link
call php artisan migrate:fresh
call php artisan db:seed
start http://127.0.0.1:8000
call php artisan serve
