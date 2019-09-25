<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Users\Providers\RouteServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        if ('testing' !== $this->app->environment()) {
            $this->app->register(TelescopeServiceProvider::class);
        }

        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
    }
}
