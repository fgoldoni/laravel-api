<?php

/**
 * Created by PhpStorm.
 * User: goldoni
 * Date: 24.09.18
 * Time: 21:18.
 */

namespace Modules\Orders\Repositories\Eloquent;

use App\Repositories\RepositoryAbstract;
use Modules\Orders\Entities\Order;
use Modules\Orders\Repositories\Contracts\OrdersRepository;

/**
 * Class EloquentOrdersRepository.
 */
class EloquentOrdersRepository extends RepositoryAbstract implements OrdersRepository
{
    public function model()
    {
        return Order::class;
    }
}
