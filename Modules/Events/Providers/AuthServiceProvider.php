<?php

namespace Modules\Events\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Events\Entities\Event;
use Modules\Events\Policies\EventPolicy;

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
