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
     */
    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * Set hidden fields.
     *
     * @param $model
     */
    public function apply($model): Builder
    {
        return $model->setHidden($this->fields);
    }
}
