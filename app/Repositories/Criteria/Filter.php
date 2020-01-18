<?php

/**
 * Created by PhpStorm.
 * User: goldoni
 * Date: 24.09.18
 * Time: 23:14.
 */

namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class Filter.
 */
class Filter implements CriterionInterface
{
    /**
     * @var string
     */
    private $search;

    /**
     * Filter constructor.
     */
    public function __construct(string $search = null)
    {
        $this->search = $search;
    }

    /**
     * @param $model
     */
    public function apply($model): Builder
    {
        return $model->search($this->search);
    }
}
