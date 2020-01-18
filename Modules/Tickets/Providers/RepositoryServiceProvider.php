<?php

namespace Modules\Tickets\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Tickets\Repositories\Contracts\TicketsRepository;
use Modules\Tickets\Repositories\Eloquent\EloquentTicketsRepository;

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
        $this->app->bind(TicketsRepository::class, EloquentTicketsRepository::class);
    }
}
