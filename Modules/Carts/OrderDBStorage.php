<?php

namespace Modules\Carts;

use Darryldecode\Cart\CartCollection;
use Modules\Carts\Entities\Order;

/**
 * Class DBStorage.
 */
class OrderDBStorage
{
    public function has($key)
    {
        return Order::find($key);
    }

    public function get($key)
    {
        if ($this->has($key)) {
            return new CartCollection(Order::find($key)->cart_data);
        }

        return [];
    }

    public function put($key, $value)
    {
        if ($row = Order::find($key)) {
            $row->cart_data = $value;
            $row->save();
        } else {
            Order::create([
                'id'        => $key,
                'cart_data' => $value
            ]);
        }
    }
}
