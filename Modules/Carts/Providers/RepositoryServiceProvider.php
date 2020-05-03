<?php

namespace Modules\Carts\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Carts\Repositories\Contracts\CartsRepository;
use Modules\Carts\Repositories\Contracts\OrderListsRepository;
use Modules\Carts\Repositories\Eloquent\EloquentCartsRepository;
use Modules\Carts\Repositories\Eloquent\EloquentOrderListsRepository;

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
        $this->app->bind(OrderListsRepository::class, EloquentOrderListsRepository::class);
    }
}
