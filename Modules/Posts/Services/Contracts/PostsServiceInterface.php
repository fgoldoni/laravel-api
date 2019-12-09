<?php

namespace Modules\Posts\Services\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface PostsServiceInterface
{
    public function getPosts(): Collection;
}
