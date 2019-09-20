<?php

namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class Hidden.
 */
class Hidden
{
    /**
     * @var array
     */
    private $fields;

    /**
     * Hidden constructor.
     *
     * @param array $fields
     */
    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * Set hidden fields.
     *
     * @param $model
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply($model): Builder
    {
        return $model->setHidden($this->fields);
    }
}
