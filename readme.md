# Time manager

[![Build Status](https://travis-ci.com/argraindas/time-manager.svg?token=1S5s1HzBzsDBDM8zZfNs&branch=master)](https://travis-ci.com/argraindas/time-manager)

This is my tiny time manager app

## INSTALLATION

### Prerequisites

* To run this project, you must have PHP 7 installed.
* You should setup a host on your web server for your local domain. 
* If you want use Redis as your cache driver you need to install the Redis Server. You can either use homebrew on a Mac or compile from source (https://redis.io/topics/quickstart). 

### Step 1 

```bash
git clone git@github.com:argraindas/time-manager.git
cd time-manager
composer install
npm install
php artisan ziggy:generate "resources/js/ziggy.js"
npm run dev
```    
    
### Step 2
Copy and rename `.env.example` to `.env`. Don't forget to check those settings.   

### Step 3
If needed install Telescope
    
```bash 
php artisan telescope:install
php artisan migrate
```

## DEVELOPMENT

### UPDATE
```bash 
composer self-update
composer update
npm update
```

## Custom (if needed)
### Laravel 5 IDE Helper Generator for laravel autocompletion and Ziggy routes
```bash 
php artisan ide-helper:generate
php artisan ide-helper:models --write
php artisan ide-helper:meta
php artisan ziggy:generate "resources/js/ziggy.js"
```
## PRODUCTION

### Optimize
```bash 
php artisan clear-compiled 
composer dump-autoload
php artisan optimize
```
