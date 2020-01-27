<?php

namespace Modules\Transactions\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Transactions\Listeners\TransactionsSubscriber;

class EventServiceProvider extends ServiceProvider
{
    public function boot()
    {
        parent::boot();
    }

    protected $subscribe = [
        TransactionsSubscriber::class
    ];
}
