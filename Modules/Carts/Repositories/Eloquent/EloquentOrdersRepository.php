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
use Darryldecode\Cart\Facades\CartFacade;
use Illuminate\Support\Facades\Auth;
use Modules\Carts\Entities\Order;
use Modules\Carts\Repositories\Contracts\OrdersRepository;
use Modules\Events\Transformers\EventCartCollection;
use Modules\Tickets\Entities\Ticket;
use Modules\Tickets\Transformers\TicketCartCollection;

/**
 * Class EloquentCartsRepository.
 */
class EloquentOrdersRepository extends RepositoryAbstract implements OrdersRepository
{
    public function model()
    {
        return Order::class;
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
