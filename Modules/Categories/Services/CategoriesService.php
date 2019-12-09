<?php

namespace Modules\Categories\Services;

use App\Repositories\Criteria\OrderBy;
use App\Repositories\Criteria\WhereIsRoot;
use App\Repositories\Criteria\WithTrashed;
use App\Services\ServiceAbstract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Categories\Entities\Category;
use Modules\Categories\Repositories\Contracts\CategoriesRepository;
use Modules\Categories\Services\Contracts\CategoriesServiceInterface;

/**
 * Class CategoriesService.
 */
class CategoriesService extends ServiceAbstract implements CategoriesServiceInterface
{
    public function __construct()
    {
    }

    public function getCategories(): Collection
    {
        return $this->resolveRepository()->withCriteria([
            new WhereIsRoot()
        ])->all();
    }

    public function getChildren(int $id): Collection
    {
        return $this->resolveRepository()->findWhere('parent_id', $id);
    }

    public function create(array $attributes): Category
    {
        return $this->resolveRepository()->create($attributes);
    }

    /**
     * @return mixed
     */
    protected function repository()
    {
        return CategoriesRepository::class;
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
