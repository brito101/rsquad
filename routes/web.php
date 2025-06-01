<?php

use App\Http\Controllers\Academy\AcademyController;
use App\Http\Controllers\Academy\CourseController as AcademyCourseController;
use App\Http\Controllers\Academy\UserController as AcademyUserController;
use App\Http\Controllers\Admin\ACL\PermissionController;
use App\Http\Controllers\Admin\ACL\RoleController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ChangelogController;
use App\Http\Controllers\Admin\CheatSheetCategoryController;
use App\Http\Controllers\Admin\CheatSheetController;
use App\Http\Controllers\Admin\ClassroomController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\CourseCategoryController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\CourseModuleController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Site\AboutController;
use App\Http\Controllers\Site\BlogController as SiteBlogController;
use App\Http\Controllers\Site\CheatSheetController as SiteCheatSheetController;
use App\Http\Controllers\Site\ContactController as SiteContactController;
use App\Http\Controllers\Site\CookieController;
use App\Http\Controllers\Site\CourseController as SiteCourseController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\TermController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['middleware' => ['access']], function () {
    /** Site */
    Route::name('site.')->group(function () {
        /** Home */
        Route::get('/', [HomeController::class, 'index'])->name('home');

        /** Courses */
        Route::get('/cursos/{uri}', [SiteCourseController::class, 'show'])->name('courses.show');
        Route::get('/cursos', [SiteCourseController::class, 'index'])->name('courses');

        /** Blog */
        Route::get('/blog/{uri}', [SiteBlogController::class, 'post'])->name('blog.post');
        Route::get('/blog', [SiteBlogController::class, 'index'])->name('blog');
        Route::get('/blog/em/{category}', [SiteBlogController::class, 'category'])->name('blog.category');

        /** Cheat Sheet */
        Route::get('/cheat-sheets/{uri}', [SiteCheatSheetController::class, 'post'])->name('cheat-sheets.post');
        Route::get('/cheat-sheets', [SiteCheatSheetController::class, 'index'])->name('cheat-sheets');

        /** About */
        Route::get('/sobre', [AboutController::class, 'index'])->name('about');

        /** Terms */
        Route::get('/termos', [TermController::class, 'index'])->name('terms');

        /** Contact */
        Route::get('/contato', [SiteContactController::class, 'index'])->name('contact');
        Route::middleware(['throttle:contact'])->group(function () {
            Route::post('/contato', [SiteContactController::class, 'send'])
                ->name('contact.send');
        });

        /** Cookie */
        Route::post('/cookie-consent', [CookieController::class, 'index'])->name('cookie.consent');
    });
});

Route::group(['middleware' => ['auth', 'access']], function () {
    /** Adminstration */
    Route::get('admin', [AdminController::class, 'index'])->name('admin.home');
    Route::prefix('admin')->name('admin.')->group(function () {
        /** Chart home */
        Route::get('/chart', [AdminController::class, 'chart'])->name('home.chart');

        /** Users */
        Route::get('/user/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::post('user/google2fa', [UserController::class, 'google2fa'])->name('user.google2fa');
        Route::resource('users', UserController::class)->except(['show']);

        /** Sutendts */
        Route::resource('students', StudentController::class);

        /** Course Categories */
        Route::resource('course-categories', CourseCategoryController::class)->except(['show']);

        /** Courses */
        Route::get('courses/{course}/modules', [CourseController::class, 'modules'])->name('courses.modules');
        Route::get('courses/{course}/classes', [CourseController::class, 'classes'])->name('courses.classes');
        Route::get('courses/{course}/students', [CourseController::class, 'students'])->name('courses.students');
        Route::resource('courses', CourseController::class)->except(['show']);

        /** Course Modules */
        Route::get('course-modules/{module}/classes', [CourseModuleController::class, 'classes'])->name('course-modules.classes');
        Route::resource('course-modules', CourseModuleController::class)->except(['show']);

        /** Classes */
        Route::resource('classes', ClassroomController::class)->except(['show']);

        /** Blog */
        Route::resource('blog', BlogController::class)->except('show');
        Route::resource('blog-categories', BlogCategoryController::class)->except('show');

        /** Blog */
        Route::resource('cheat-sheets', CheatSheetController::class)->except('show');
        Route::resource('cheat-sheets-categories', CheatSheetCategoryController::class)->except('show');

        /** Contacts */
        Route::resource('contacts', ContactController::class)->except(['show', 'edit', 'update']);

        /**
         * ACL
         * */
        /** Permissions */
        Route::resource('permission', PermissionController::class)->except(['show']);

        /** Roles */
        Route::get('role/{role}/permission', [RoleController::class, 'permissions'])->name('role.permissions');
        Route::put('role/{role}/permission/sync', [RoleController::class, 'permissionsSync'])->name('role.permissionsSync');
        Route::resource('role', RoleController::class)->except(['show']);

        /** Changelog */
        Route::get('/changelog', [ChangelogController::class, 'index'])->name('changelog');
    });

    /** Academy */
    Route::prefix('academy')->name('academy.')->group(function () {
        /** Users */
        Route::get('/', [AcademyController::class, 'index'])->name('home');
        Route::get('/user/edit', [AcademyUserController::class, 'edit'])->name('user.edit');
        Route::put('/user/edit', [AcademyUserController::class, 'update'])->name('user.update');
        /** Courses */
        Route::get('/courses', [AcademyCourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/{course}', [AcademyCourseController::class, 'show'])->name('courses.show');
    });
});

Auth::routes([
    'register' => true,
]);

Route::fallback(function () {
    abort('404');
});
