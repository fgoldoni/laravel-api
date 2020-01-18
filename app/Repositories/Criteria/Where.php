<?php

/**
 * Created by PhpStorm.
 * User: goldoni
 * Date: 18.11.18
 * Time: 15:17.
 */

namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class Where.
 */
class Where
{
    /**
     * @var string
     */
    private $column;
    /**
     * @var string
     */
    private $value;

    /**
     * Where constructor.
     */
    public function __construct(string $column, string $value)
    {
        $this->column = $column;
        $this->value = $value;
    }

    /**
     * @param $model
     */
    public function apply($model): Builder
    {
        return $model->where($this->column, $this->value);
    }
}
