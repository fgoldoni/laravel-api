<?php

namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class Take.
 */
class Take
{
    /**
     * @var int
     */
    private $number;

    /**
     * Take constructor.
     */
    public function __construct(int $number)
    {
        $this->number = $number;
    }

    /**
     * Set hidden fields.
     *
     * @param $model
     */
    public function apply($model): Builder
    {
        return $model->take($this->number);
    }
}
