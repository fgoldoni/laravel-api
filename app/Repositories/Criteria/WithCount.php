<?php

namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class WithCount.
 */
class WithCount
{
    /**
     * @var string
     */
    private $relations;

    /**
     * WithCount constructor.
     *
     * @param string $relations
     */
    public function __construct(string $relations)
    {
        $this->relations = $relations;
    }

    /**
     * @param $model
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply($model): Builder
    {
        return $model->withCount($this->relations);
    }
}
