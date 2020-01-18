<?php

namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class Where.
 */
class Search
{
    /**
     * @var \Closure
     */
    private $closure;

    /**
     * Where constructor.
     */
    public function __construct(\Closure $closure)
    {
        $this->closure = $closure;
    }

    /**
     * @param $model
     */
    public function apply($model): Builder
    {
        if (isset($this->closure) && \is_callable($this->closure)) {
            return $model->where($this->closure);
        }

        return $this;
    }
}
