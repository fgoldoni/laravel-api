<?php

namespace Modules\Categories\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Categories\Repositories\Contracts\CategoriesRepository;
use Modules\Categories\Repositories\Eloquent\EloquentCategoriesRepository;

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
        $this->app->bind(CategoriesRepository::class, EloquentCategoriesRepository::class);
    }
}
