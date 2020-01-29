<?php

namespace Modules\Carts\Repositories\Contracts;

use Modules\Tickets\Entities\Ticket;

interface CartsRepository
{
    public function addCart(Ticket $ticket, int $quantity);

    public function details();

    public function updateCart(Ticket $ticket, int $quantity);

    public function deleteCart(int $id);

    public function clear();

    public function addCoupon(string $coupon);
}
