<?php

/**
 * Created by PhpStorm.
 * User: emere
 * Date: 09/10/2018
 * Time: 11:47.
 */

namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class Select.
 */
class Select
{
    /**
     * @var mixed
     */
    private $fields;

    /**
     * Select constructor.
     *
     * @param mixed ...$fields
     */
    public function __construct(...$fields)
    {
        $this->fields = $fields;
    }

    /**
     * @param $model
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply($model): Builder
    {
        return $model->select($this->fields);
    }
}
