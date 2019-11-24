<?php

namespace App\Services\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ServiceInterface
{
    public function parseRequest($request);

    public function paginate($request): LengthAwarePaginator;

    public function all(): Collection;

    public function store(array $attributes);

    public function update(int $id, array $attributes);

    public function delete(int $id);

    public function forceDelete(int $id);

    public function restore(int $id);

    public function find(int $id);

    public function findWhereIn($field, array $values, $columns = ['*']);

    public function firstOrNew(array $attributes);

    public function sync($id, $relation, $attributes, $detaching = true);
}
