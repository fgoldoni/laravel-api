<?php

namespace Modules\Carts\Providers;

use Darryldecode\Cart\Cart;
use Illuminate\Support\ServiceProvider;
use Modules\Carts\OrderDBStorage;

/**
 * Class WishListProvider.
 */
class OrderListProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('orderlist', function ($app) {
            $storage = new OrderDBStorage();
            $events = $app['events'];
            $instanceName = 'order';
            $session_key = '88uuiioo99888';

            return new Cart(
                $storage,
                $events,
                $instanceName,
                $session_key,
                config('shopping_cart')
            );
        });
    }
}
