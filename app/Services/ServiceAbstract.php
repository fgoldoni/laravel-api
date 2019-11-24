<?php

namespace App\Services;

use App\Repositories\Criteria\EagerLoad;
use App\Repositories\Criteria\OrderBy;
use App\Repositories\Criteria\WithTrashed;
use App\Repositories\RepositoryAbstract;
use App\Services\Contracts\ServiceInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class ServiceAbstract.
 */
abstract class ServiceAbstract implements ServiceInterface
{
    protected $repository;

    /**
     * @return mixed
     */
    abstract protected function repository();

    protected function resolveRepository()
    {
        $repository = app()->make($this->repository());

        if (!$repository instanceof RepositoryAbstract) {
            throw new Exception(
                "Class {$this->repository()} must be an instance of App\\Repositories\\RepositoryAbstract"
            );
        }

        return $repository;
    }

    public function parseRequest($request)
    {
        return [
            $request->get('per_page', 10),
            explode('|', $request->get('sort', 'id|asc')),
            $request->get('filter')
        ];
    }

    public function paginate($request): LengthAwarePaginator
    {
        [$perPage, $sort, $search] = $this->parseRequest($request);

        return $this->resolveRepository()->withCriteria([
            new WithTrashed(),
            new OrderBy($sort[0], $sort[1])
        ])->paginate($perPage);
    }

    public function all(): Collection
    {
        return $this->resolveRepository()->withCriteria([
            new WithTrashed()
        ])->all();
    }

    public function store(array $attributes)
    {
        return $this->resolveRepository()->create($attributes);
    }

    public function update(int $id, array $attributes)
    {
        return $this->resolveRepository()->update(
            $id,
            $attributes
        );
    }

    public function delete(int $id)
    {
        return $this->resolveRepository()->delete($id);
    }

    public function forceDelete(int $id)
    {
        return $this->resolveRepository()->forceDelete($id);
    }

    public function restore(int $id)
    {
        return $this->resolveRepository()->restore($id);
    }

    public function find(int $id)
    {
        return $this->resolveRepository()->find($id);
    }

    public function firstOrNew(array $attributes)
    {
        return $this->resolveRepository()->firstOrNew($attributes);
    }

    public function findWhereIn($field, array $values, $columns = ['*'])
    {
        return $this->resolveRepository()->findWhereIn('id', $values, $columns = ['*']);
    }

    public function sync($id, $relation, $attributes, $detaching = true)
    {
        $this->resolveRepository()->sync($id, $relation, $attributes);
    }
}
