<?php

namespace Modules\Events\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Events\Listeners\EventsSubscriber;

class EventServiceProvider extends ServiceProvider
{
    public function boot()
    {
        parent::boot();
    }

    protected $subscribe = [
        EventsSubscriber::class
    ];
}
