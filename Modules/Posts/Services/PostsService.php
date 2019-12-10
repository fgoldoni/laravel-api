<?php

namespace Modules\Posts\Services;

use App\Repositories\Criteria\EagerLoad;
use App\Repositories\Criteria\OrderBy;
use App\Repositories\Criteria\WithTrashed;
use App\Services\ServiceAbstract;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Posts\Entities\Post;
use Modules\Posts\Repositories\Contracts\PostsRepository;
use Modules\Posts\Services\Contracts\PostsServiceInterface;

/**
 * Class PostsService.
 */
class PostsService extends ServiceAbstract implements PostsServiceInterface
{
    /**
     * @var \Illuminate\Auth\AuthManager
     */
    private $auth;

    public function __construct(AuthManager $auth)
    {
        $this->auth = $auth;
    }

    public function getPosts(): Collection
    {
        return $this->resolveRepository()->withCriteria([
            new WithTrashed(),
            new EagerLoad(['categories' => function ($query) {
                $query->select('categories.id', 'categories.name');
            }, 'user' => function ($query) {
                $query->select('users.id', 'users.first_name', 'users.last_name');
            }])
        ])->all();
    }

    public function storePost(array $attributes = [], array $categories = [], array $tags = null): Post
    {
        $post = $this->resolveRepository()->create(
            array_merge(
                $attributes,
                ['user_id' => $this->auth->user()->id]
            )
        );

        $this->resolveRepository()->sync($post->id, 'categories', $categories);
        $post->saveTags($tags);

        return $post->fresh();
    }

    public function updatePost(int $id, array $attributes = [], array $categories = [], array $tags = null): Post
    {
        $post = $this->resolveRepository()->update(
            $id,
            $attributes
        );

        $this->resolveRepository()->sync($post->id, 'categories', $categories);
        $post->saveTags($tags);

        return $post->fresh();
    }

    /**
     * @return mixed
     */
    protected function repository()
    {
        return PostsRepository::class;
    }

    public function paginate($request): LengthAwarePaginator
    {
        [$perPage, $sort, $search] = $this->parseRequest($request);

        return $this->resolveRepository()->withCriteria([
            new WithTrashed(),
            new OrderBy($sort[0], $sort[1])
        ])->paginate($perPage);
    }
}
