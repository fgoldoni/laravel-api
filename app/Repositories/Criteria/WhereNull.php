<?php

/**
 * Created by PhpStorm.
 * User: goldoni
 * Date: 20.11.18
 * Time: 17:08.
 */

namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class WhereNull.
 */
class WhereNull
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
        return $model->whereNull($this->column);
    }
}
