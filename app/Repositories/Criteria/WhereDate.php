<?php

/**
 * Created by PhpStorm.
 * User: goldoni
 * Date: 17.11.18
 * Time: 23:53.
 */

namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class WhereDate.
 */
class WhereDate
{
    /**
     * @var string
     */
    private $column;

    private $value;
    /**
     * @var string
     */
    private $op;

    /**
     * WhereDate constructor.
     *
     * @param string $column
     * @param string $op
     * @param        $value
     */
    public function __construct(string $column, string $op, $value)
    {
        $this->column = $column;
        $this->value = $value;
        $this->op = $op;
    }

    /**
     * @param $model
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply($model): Builder
    {
        return $model->whereDate($this->column, $this->op, $this->value);
    }
}
