<?php

namespace Modules\Carts\Providers;

use Darryldecode\Cart\Cart;
use Illuminate\Support\ServiceProvider;
use Modules\Carts\DBStorage;

/**
 * Class WishListProvider.
 */
class WishListProvider extends ServiceProvider
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
        $this->app->singleton('wishlist', function ($app) {
            $storage = new DBStorage();
            $events = $app['events'];
            $instanceName = 'cart_2';
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
