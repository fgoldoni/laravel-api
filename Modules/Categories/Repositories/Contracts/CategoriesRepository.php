<?php

namespace Modules\Categories\Repositories\Contracts;

interface CategoriesRepository
{
    public function getSiblingsCategories(int $key, $model);

    /**
     * @return mixed
     */
    public function siblings(string $slug, string $model = null);
}
