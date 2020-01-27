<?php

namespace Modules\Carts\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Carts\Repositories\Contracts\CartsRepository;
use Modules\Carts\Repositories\Contracts\OrdersRepository;
use Modules\Carts\Repositories\Eloquent\EloquentCartsRepository;
use Modules\Carts\Repositories\Eloquent\EloquentOrdersRepository;

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
        $this->app->bind(CartsRepository::class, EloquentCartsRepository::class);
        $this->app->bind(OrdersRepository::class, EloquentOrdersRepository::class);
    }
}
