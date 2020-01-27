<?php

namespace Modules\Transactions\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Transactions\Repositories\Contracts\TransactionsRepository;
use Modules\Transactions\Repositories\Eloquent\EloquentTransactionsRepository;

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
        $this->app->bind(TransactionsRepository::class, EloquentTransactionsRepository::class);
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
