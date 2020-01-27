<?php

namespace Modules\Stripe\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Stripe\Repositories\Contracts\StripeRepository;
use Modules\Stripe\Repositories\Eloquent\EloquentStripeRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    public function boot()
    {
        $this->app->bind(StripeRepository::class, EloquentStripeRepository::class);
    }
}
