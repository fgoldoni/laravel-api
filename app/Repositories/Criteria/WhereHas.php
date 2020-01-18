<?php

namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class WhereHas.
 */
class WhereHas
{
    /**
     * @var string
     */
    private $relation;
    private $closure;

    /**
     * WhereHas constructor.
     *
     * @param $closure
     */
    public function __construct(string $relation, $closure)
    {
        $this->relation = $relation;
        $this->closure = $closure;
    }

    /**
     * @param $model
     */
    public function apply($model): Builder
    {
        return $model->whereHas($this->relation, $this->closure);
    }
}
