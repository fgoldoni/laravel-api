<?php

/**
 * Created by PhpStorm.
 * User: emere
 * Date: 23/10/2018
 * Time: 12:25.
 */

namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class whereIsRoot.
 */
class WhereIsRoot
{
    /**
     * @param $model
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply($model): Builder
    {
        return $model->whereIsRoot();
    }
}
