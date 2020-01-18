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
     */
    public function __construct(string $column)
    {
        $this->column = $column;
    }

    /**
     * @param $model
     */
    public function apply($model): Builder
    {
        return $model->whereNotNull($this->column);
    }
}
