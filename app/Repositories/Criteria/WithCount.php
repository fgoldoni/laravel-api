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
     */
    public function __construct(string $relations)
    {
        $this->relations = $relations;
    }

    /**
     * @param $model
     */
    public function apply($model): Builder
    {
        return $model->withCount($this->relations);
    }
}
