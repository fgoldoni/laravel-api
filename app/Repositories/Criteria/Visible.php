<?php

namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class Visible.
 */
class Visible
{
    /**
     * @var array
     */
    private $fields;

    /**
     * Visible constructor.
     */
    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * Set visible fields.
     *
     * @param $model
     */
    public function apply($model): Builder
    {
        return $model->setVisible($this->fields);
    }
}
