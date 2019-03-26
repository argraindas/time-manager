# Time manager

[![Build Status](https://travis-ci.com/argraindas/time-manager.svg?token=1S5s1HzBzsDBDM8zZfNs&branch=master)](https://travis-ci.com/argraindas/time-manager)

## INSTALLATION

### 1. Installing core

    composer install
    npm install
    
### 2. Checking `.env` file     

### 3. Telescope
 
    php artisan telescope:install
    php artisan migrate


## DEVELOPMENT

### UPDATE

    composer self-update
    composer update
    npm update

## Import Ziggy routes from backend

    php artisan ziggy:generate "resources/js/ziggy.js"

### Laravel 5 IDE Helper Generator for laravel autocompletion

    php artisan ide-helper:generate - phpDoc generation for Laravel Facades
    php artisan ide-helper:models - phpDocs for models
    php artisan ide-helper:meta - PhpStorm Meta file

## PRODUCTION

### Optimize

    php artisan clear-compiled 
    composer dump-autoload
    php artisan optimize




