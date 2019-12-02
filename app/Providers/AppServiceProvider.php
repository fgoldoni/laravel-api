<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Activities\Providers\ActivitiesServiceProvider;
use Modules\Attachments\Providers\AttachmentsServiceProvider;
use Modules\Roles\Providers\RolesServiceProvider;
use Modules\Users\Providers\UsersServiceProvider;

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
        $this->app->register(UsersServiceProvider::class);
        $this->app->register(RolesServiceProvider::class);
        $this->app->register(ActivitiesServiceProvider::class);
        $this->app->register(AttachmentsServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
    }
}
