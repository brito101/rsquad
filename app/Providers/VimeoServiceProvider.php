<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Vimeo\Vimeo;

class VimeoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(Vimeo::class, function ($app) {
            return new Vimeo(
                config('services.vimeo.client_id'),
                config('services.vimeo.client_secret'),
                config('services.vimeo.access_token')
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
