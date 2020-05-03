<?php

/**
 * Created by PhpStorm.
 * Role: goldoni
 * Date: 24.09.18
 * Time: 21:18.
 */

namespace Modules\Carts\Repositories\Eloquent;

use App\Repositories\RepositoryAbstract;
use Darryldecode\Cart\CartCondition;
use Illuminate\Support\Facades\Auth;
use Modules\Carts\Entities\OrderList;
use Modules\Carts\Repositories\Contracts\OrderListsRepository;

/**
 * Class EloquentCartsRepository.
 */
class EloquentOrderListsRepository extends RepositoryAbstract implements OrderListsRepository
{
    public function model()
    {
        return OrderList::class;
    }

    public function details()
    {
        $items = [];
        $userId = Auth::id();
        $orderList = app('orderlist');
        $conditions = $orderList->session($userId)->getConditions();

        $subTotalConditions = $conditions->filter(function (CartCondition $condition) {
            return 'subtotal' === $condition->getTarget();
        })->map(function (CartCondition $c) use ($userId) {
            return [
                'name'   => $c->getName(),
                'type'   => $c->getType(),
                'target' => $c->getTarget(),
                'value'  => $c->getValue(),
            ];
        });

        $totalConditions = $conditions->filter(function (CartCondition $condition) {
            return 'total' === $condition->getTarget();
        })->map(function (CartCondition $c) {
            return [
                'name'   => $c->getName(),
                'type'   => $c->getType(),
                'target' => $c->getTarget(),
                'value'  => $c->getValue(),
            ];
        });

        $orderList->session($userId)->getContent()->sort()->each(function ($item) use (&$items) {
            $items[] = $item;
        });

        return [
            'items'                           => $items,
            'total_quantity'                  => $orderList->session($userId)->getTotalQuantity(),
            'sub_total'                       => $orderList->session($userId)->getSubTotal(),
            'total'                           => $orderList->session($userId)->getTotal(),
            'cart_sub_total_conditions_count' => $subTotalConditions->count(),
            'cart_total_conditions_count'     => $totalConditions->count(),
        ];
    }
}
