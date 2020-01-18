<?php

namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;

interface CriterionInterface
{
    /**
     * @param $model
     */
    public function apply($model): Builder;
}
