<?php

namespace Modules\Tickets\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Tickets\Entities\Event;
use Modules\Tickets\Policies\EventPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Event::class => EventPolicy::class
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
