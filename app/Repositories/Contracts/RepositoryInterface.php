<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface RepositoryInterface
{
    public function all(): Collection;

    public function get($columns = ['*']);

    public function find(int $id);

    public function first($columns = ['*']);

    public function findWhere(string $column, $value);

    public function findWhereDate(string $column, string $op = '=', $value = null, $columns = ['*']);

    public function findWhereFirst(string $column, $value);

    public function findWhereIn($field, array $values, $columns = ['*']);

    public function findWhereNotIn($field, array $values, $columns = ['*']);

    public function paginate(int $perPage): LengthAwarePaginator;

    public function count(): int;

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);

    public function forceDelete(int $id);

    public function restore(int $id);

    public function pluck($column, $key = null);

    public function sync($id, $relation, $attributes, $detaching = true);

    public function syncWithoutDetaching($id, $relation, $attributes);

    public function firstOrNew(array $attributes = []);

    public function firstOrCreate(array $attributes = []);

    public function setHidden(string $attribute);

    public function setVisible(string $attribute);
}
