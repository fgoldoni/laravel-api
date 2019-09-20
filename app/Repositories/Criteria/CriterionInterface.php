<?php

namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;

interface CriterionInterface
{
    /**
     * @param $model
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply($model): Builder;
}
