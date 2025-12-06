# RSquad Academy

<p align="center">
<img src="screenshots/logo.png" alt="RSquad Academy"/>
</p>

## Project with Laravel 12 and Docker with Laravel Pint, PEST, Debugar, AdminLTE3, DataTables server side and Spatie ACL

### Resources

-   Basic user controller with 2FA authentication
-   Visitors log
-   API routes with JWT auth
-   Courses with categories, classes and students
    -   Vimeo video integration with upload and management
    -   External video links support (YouTube, etc)
    -   Custom thumbnails for videos
    -   Student progress tracking with watched/completed status
    -   Individual video page with navigation between classes
    -   AJAX video deletion without page reload
-   Blog
-   Cheat Sheet
-   Contacts

<p align="center">
<img src="screenshots/home.png" alt="RSquad Academy"/>
</p>

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