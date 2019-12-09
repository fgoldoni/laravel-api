<?php

namespace Modules\Categories\Services\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Modules\Categories\Entities\Category;

interface CategoriesServiceInterface
{
    public function getCategories(): Collection;

    public function getChildren(int $id): Collection;

    public function create(array $attributes): Category;
}
