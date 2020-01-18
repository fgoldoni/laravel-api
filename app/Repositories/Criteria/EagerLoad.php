<?php

/**
 * Created by PhpStorm.
 * User: goldoni
 * Date: 24.09.18
 * Time: 22:46.
 */

namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class EagerLoad.
 */
class EagerLoad implements CriterionInterface
{
    /**
     * @var array
     */
    protected $relations;

    /**
     * EagerLoad constructor.
     */
    public function __construct(array $relations)
    {
        $this->relations = $relations;
    }

    /**
     * @param $model
     */
    public function apply($model): Builder
    {
        return $model->with($this->relations);
    }
}
