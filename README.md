# Solutech Challenge 1.0.0
## Technologies Used
- [Laravel Framework](https://laravel.com/docs/8.x)
- [Laravel Fortify](https://laravel.com/docs/8.x/fortify)
- [Laravel Sanctum](https://laravel.com/docs/8.x/sanctum)
- [MYSQL 8.x](https://dev.mysql.com/doc/relnotes/mysql/8.0/en/) with [Eloquent ORM](https://laravel.com/docs/8.x/eloquent)
- [Vue JS (2.x)](https://vuejs.org/v2/guide/)
- [Bootstarap 4](https://getbootstrap.com/docs/4.6/getting-started/introduction/)

## Up and Running with the Project
- Clone the repository
- Copy `.env.example` file to `.env` and configure the database, APP_KEY, and other necessary constants/variables
- Install the required composer packages : `composer install` 
- Install and compile CSS and JS assets: `npm install && npm run dev`
- Spin the local server either using `php artisan serve` or using Laravel Valet

## Important Notes
- The minimum PHP version requirement by Laravel 8.x is PHP 7.3. However, PHP 7.4 or higher syntax such as arrow functions have been used in this project. Hence, PHP 7.3 will not work as expected, and it is advisable to use PHP 7.4 or higher. This project was developed and tested with PHP 8.0
- The project uses Laravel Sanctum [SPA Authentication](https://laravel.com/docs/8.x/sanctum#spa-authentication). Currently, it is configured to work with a local host and `.test` or `.ntc` valet domain. If using any other domain, please add it to configuration in `config/sanctum.php` under `stateful` or in the `SANCTUM_STATEFUL_DOMAINS` constant in `.env` file

## Todos
- Write Tests
- Do API Documentations
- Work on Version 1.1.x, to address suggestions, feedback and bug fixes
