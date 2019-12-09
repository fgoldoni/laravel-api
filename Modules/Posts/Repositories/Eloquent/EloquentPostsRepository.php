<?php

/**
 * Created by PhpStorm.
 * Role: goldoni
 * Date: 24.09.18
 * Time: 21:18.
 */

namespace Modules\Posts\Repositories\Eloquent;

use App\Repositories\RepositoryAbstract;
use Modules\Posts\Entities\Post;
use Modules\Posts\Repositories\Contracts\PostsRepository;

/**
 * Class EloquentPostsRepository.
 */
class EloquentPostsRepository extends RepositoryAbstract implements PostsRepository
{
    public function model()
    {
        return Post::class;
    }
}
