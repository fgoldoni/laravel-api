<?php

/**
 * Created by PhpStorm.
 * User: goldoni
 * Date: 24.11.18
 * Time: 15:33.
 */

namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class isPublished.
 */
class Published
{
    /**
     * @var bool
     */
    private $online;

    /**
     * isPublished constructor.
     *
     * @param bool $online
     */
    public function __construct(bool $online = true)
    {
        $this->online = $online;
    }

    /**
     * @param $model
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply($model): Builder
    {
        return $model->published($this->online);
    }
}
