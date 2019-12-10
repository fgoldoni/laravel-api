<?php

namespace Modules\Posts\Services\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Modules\Posts\Entities\Post;

interface PostsServiceInterface
{
    public function getPosts(): Collection;

    public function storePost(array $attributes = [], array $categories = [], array $tags = null): Post;

    public function updatePost(int $id, array $attributes = [], array $categories = [], array $tags = null): Post;
}
