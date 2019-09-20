<?php

/**
 * Created by PhpStorm.
 * User: goldoni
 * Date: 24.09.18
 * Time: 23:02.
 */

namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class OrderBy.
 */
class OrderBy implements CriterionInterface
{
    /**
     * @var string
     */
    private $column;
    /**
     * @var string
     */
    private $dir;

    /**
     * OrderBy constructor.
     *
     * @param string $column
     * @param string $dir
     */
    public function __construct(string $column, string $dir = 'asc')
    {
        $this->column = $column;
        $this->dir = $dir;
    }

    /**
     * @param $model
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply($model): Builder
    {
        return $model->orderBy($this->column, $this->dir);
    }
}
