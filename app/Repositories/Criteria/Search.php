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
     *
     * @param \Closure $closure
     */
    public function __construct(\Closure $closure)
    {
        $this->closure = $closure;
    }

    /**
     * @param $model
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply($model): Builder
    {
        if (isset($this->closure) && \is_callable($this->closure)) {
            return $model->where($this->closure);
        }

        return $this;
    }
}
