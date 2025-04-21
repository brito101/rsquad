<?php

use App\Http\Controllers\Academy\AcademyController;
use App\Http\Controllers\Academy\UserController as AcademyUserController;
use App\Http\Controllers\Admin\ACL\PermissionController;
use App\Http\Controllers\Admin\ACL\RoleController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ChangelogController;
use App\Http\Controllers\Admin\ClassroomController;
use App\Http\Controllers\Admin\CourseCategoryController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Site\HomeController;
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
        Route::get('/', [HomeController::class, 'index'])->name('home');
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

        /** Course Categories */
        Route::resource('course-categories', CourseCategoryController::class)->except(['show']);

        /** Courses */
        Route::get('courses/{course}/classes', [CourseController::class, 'classes'])->name('courses.classes');
        Route::resource('courses', CourseController::class)->except(['show']);

        /** Classes */
        Route::resource('classes', ClassroomController::class)->except(['show']);

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
    });
});

Auth::routes([
    'register' => false,
]);

Route::fallback(function () {
    abort('404');
});
