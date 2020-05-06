<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Activities\Providers\ActivitiesServiceProvider;
use Modules\Attachments\Providers\AttachmentsServiceProvider;
use Modules\Carts\Providers\CartsServiceProvider;
use Modules\Categories\Providers\CategoriesServiceProvider;
use Modules\Events\Providers\EventsServiceProvider;
use Modules\Orders\Providers\OrdersServiceProvider;
use Modules\Posts\Providers\PostsServiceProvider;
use Modules\Roles\Providers\RolesServiceProvider;
use Modules\Stripe\Providers\StripeServiceProvider;
use Modules\Tags\Providers\TagsServiceProvider;
use Modules\Tickets\Providers\TicketsServiceProvider;
use Modules\Transactions\Providers\TransactionsServiceProvider;
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
        $this->app->register(CategoriesServiceProvider::class);
        $this->app->register(EventsServiceProvider::class);
        $this->app->register(PostsServiceProvider::class);
        $this->app->register(TagsServiceProvider::class);
        $this->app->register(CartsServiceProvider::class);
        $this->app->register(TransactionsServiceProvider::class);
        $this->app->register(OrdersServiceProvider::class);
        $this->app->register(TicketsServiceProvider::class);
        $this->app->register(StripeServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
    }
}
