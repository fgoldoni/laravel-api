<?php

/**
 * Created by PhpStorm.
 * User: goldoni
 * Date: 24.09.18
 * Time: 22:13.
 */

namespace App\Repositories\Criteria;

/**
 * interface CriteriaInterface.
 */
interface CriteriaInterface
{
    /**
     * @param mixed ...$criteria
     *
     * @return mixed
     */
    public function withCriteria(...$criteria);
}
