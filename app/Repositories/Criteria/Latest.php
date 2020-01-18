<?php

/**
 * Created by PhpStorm.
 * User: goldoni
 * Date: 24.09.18
 * Time: 22:20.
 */

namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class LatestFirst.
 */
class Latest implements CriterionInterface
{
    /**
     * @param $model
     */
    public function apply($model): Builder
    {
        return $model->latest();
    }
}
