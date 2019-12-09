<?php

namespace Modules\Posts\Services;

use App\Repositories\Criteria\EagerLoad;
use App\Repositories\Criteria\OrderBy;
use App\Repositories\Criteria\WithTrashed;
use App\Services\ServiceAbstract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Posts\Repositories\Contracts\PostsRepository;
use Modules\Posts\Services\Contracts\PostsServiceInterface;

/**
 * Class PostsService.
 */
class PostsService extends ServiceAbstract implements PostsServiceInterface
{
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
