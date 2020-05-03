<?php

namespace Modules\Carts;

use Darryldecode\Cart\CartCollection;
use Modules\Carts\Entities\OrderList;

/**
 * Class DBStorage.
 */
class OrderListDBStorage
{
    public function has($key)
    {
        return OrderList::find($key);
    }

    public function get($key)
    {
        if ($this->has($key)) {
            return new CartCollection(OrderList::find($key)->cart_data);
        }

        return [];
    }

    public function put($key, $value)
    {
        if ($row = OrderList::find($key)) {
            $row->cart_data = $value;
            $row->save();
        } else {
            OrderList::create([
                'id'        => $key,
                'cart_data' => $value
            ]);
        }
    }
}
