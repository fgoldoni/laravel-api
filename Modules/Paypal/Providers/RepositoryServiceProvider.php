<?php

namespace Modules\Paypal\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Paypal\Repositories\Contracts\PaypalRepository;
use Modules\Paypal\Repositories\Eloquent\EloquentPaypalRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->bind(PaypalRepository::class, EloquentPaypalRepository::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
