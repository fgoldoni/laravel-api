<?php

namespace Modules\Events\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Events\Repositories\Contracts\EventsRepository;
use Modules\Events\Repositories\Eloquent\EloquentEventsRepository;

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
        $this->app->bind(EventsRepository::class, EloquentEventsRepository::class);
    }
}
