# RSquad Academy

<p align="center">
<img src="rsquadacademy.jpg" alt="RSquad Academy"/>
</p>

## Project with Laravel 12 and Docker with Laravel Pint, PEST, Debugar, AdminLTE3, DataTables server side and Spatie ACL

### Resources

-   Basic user controller
-   2FA authentication
-   Visitors log
-   API routes with JWT auth
-   Course categories module
-   Courses module
-   Classroom module
-   Students module
-   Blog module

### Usage in development environment

-   `cp .env.example .env`
-   Edit .env parameters
-   `composer install`
-   `php artisan key:generate`
-   `php artisan jwt:secret`
-   `alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'`
-   `sail artisan storage:link`
-   `sail artisan migrate --seed`
-   `sail npm install && npm run dev`

### Test login

-   user: <programmer@rsquad.com>
-   pass: 12345678
-   user: <admin@rsquad.com>
-   pass: 12345678
-   user: <instructor@rsquad.com>
-   pass: 12345678
-   user: <student@rsquad.com>
-   pass: 12345678
