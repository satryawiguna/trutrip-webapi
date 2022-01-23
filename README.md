# loopit-webapi

## Project setup (Manual)
```
cd into root project folder
composer install
copy .env.example .env
```

## Adjust some configuration on .env file
```
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=loopit
DB_USERNAME=xxx
DB_PASSWORD=xxx
DB_PREFIX=loopit_

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=xxx
MAIL_PASSWORD=xxx
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@loopit.co
MAIL_FROM_NAME="${APP_NAME}"
```

### Compiles and hot-reloads for development
```
php artisan migrate
php artisan passport:install --uuid --no-interaction
php artisan db:seed

create folder at:
- storage/app/profile
- storage/app/product

php artisan media:link

php artisan serve
```

### Run unit test
```
php artisan test
```
