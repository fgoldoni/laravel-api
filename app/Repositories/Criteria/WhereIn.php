<?php

/**
 * Created by PhpStorm.
 * User: emere
 * Date: 09/10/2018
 * Time: 16:59.
 */

namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class WhereIn.
 */
class WhereIn
{
    /**
     * @var string
     */
    private $column;
    /**
     * @var array
     */
    private $values;

    /**
     * OrderBy constructor.
     *
     * @param array $values
     */
    public function __construct(string $column, array $values = null)
    {
        $this->column = $column;
        $this->values = $values;
    }

    /**
     * @param $model
     */
    public function apply($model): Builder
    {
        return null === $this->values ? $model->newQuery() : $model->whereIn($this->column, $this->values);
    }
}
