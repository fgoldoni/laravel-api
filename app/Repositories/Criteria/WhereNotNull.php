<?php

/**
 * Created by PhpStorm.
 * User: goldoni
 * Date: 20.11.18
 * Time: 17:14.
 */

namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class WhereNotNull.
 */
class WhereNotNull
{
    /**
     * @var string
     */
    private $column;

    /**
     * WhereNull constructor.
     *
     * @param string $column
     */
    public function __construct(string $column)
    {
        $this->column = $column;
    }

    /**
     * @param $model
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply($model): Builder
    {
        return $model->whereNotNull($this->column);
    }
}
