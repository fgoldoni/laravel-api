<?php

namespace Modules\Posts\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Posts\Repositories\Contracts\PostsRepository;
use Modules\Posts\Repositories\Eloquent\EloquentPostsRepository;

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
        $this->app->bind(PostsRepository::class, EloquentPostsRepository::class);
    }
}
