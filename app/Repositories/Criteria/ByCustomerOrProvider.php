<?php

/**
 * Created by PhpStorm.
 * User: goldoni
 * Date: 24.09.18
 * Time: 22:40.
 */

namespace App\Repositories\Criteria;

use App\Flag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

/**
 * Class ByUser.
 */
class ByCustomerOrProvider implements CriterionInterface
{
    /**
     * @var int
     */
    private $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function apply($model): Builder
    {
        if (Auth::user()->hasPermissionTo(Flag::PERMISSION_ADMIN)) {
            return $model->newQuery();
        }

        return $model->where('customer_id', $this->userId)->orWhere('provider_id', $this->userId);
    }
}
