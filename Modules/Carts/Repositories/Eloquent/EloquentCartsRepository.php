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
use Modules\Carts\Entities\Cart as Model;
use Modules\Carts\Repositories\Contracts\CartsRepository;
use Modules\Events\Transformers\EventCartCollection;
use Modules\Tickets\Entities\Ticket;
use Modules\Tickets\Transformers\TicketCartCollection;

/**
 * Class EloquentCartsRepository.
 */
class EloquentCartsRepository extends RepositoryAbstract implements CartsRepository
{
    public function model()
    {
        return Model::class;
    }

    public function addCart(Ticket $ticket, int $quantity)
    {
        $customAttributes = new EventCartCollection($ticket->event);

        CartFacade::session(Auth::id())->add([
            'id'              => $ticket->id,
            'name'            => $ticket->name,
            'price'           => $ticket->price,
            'quantity'        => $quantity,
            'attributes'      => $customAttributes,
            'associatedModel' => new TicketCartCollection($ticket)
        ]);

        return CartFacade::session(Auth::id())->getContent();
    }

    public function details()
    {
        $items = [];
        $userId = Auth::id();
        $conditions = CartFacade::session($userId)->getConditions();

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

        CartFacade::session($userId)->getContent()->sort()->each(function ($item) use (&$items) {
            $items[] = $item;
        });

        return [
            'items'                           => $items,
            'total_quantity'                  => CartFacade::session($userId)->getTotalQuantity(),
            'sub_total'                       => CartFacade::session($userId)->getSubTotal(),
            'total'                           => CartFacade::session($userId)->getTotal(),
            'cart_sub_total_conditions_count' => $subTotalConditions->count(),
            'cart_total_conditions_count'     => $totalConditions->count(),
        ];
    }

    public function updateCart(Ticket $ticket, int $quantity)
    {
        $customAttributes = new EventCartCollection($ticket->event);

        CartFacade::session(Auth::id())->remove($ticket->id);

        CartFacade::session(Auth::id())->add([
            'id'              => $ticket->id,
            'name'            => $ticket->name,
            'price'           => $ticket->price,
            'quantity'        => $quantity,
            'attributes'      => $customAttributes,
            'associatedModel' => new TicketCartCollection($ticket)
        ]);

        return CartFacade::session(Auth::id())->getContent();
    }

    public function deleteCart(int $id)
    {
        CartFacade::session(Auth::id())->remove($id);

        return CartFacade::session(Auth::id())->getContent();
    }

    public function clear()
    {
        CartFacade::session(Auth::id())->clear();
    }
}