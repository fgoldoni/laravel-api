<?php

namespace Modules\Orders\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface OrdersRepository
{
    public function getOrders(array $transactions = []): Collection;
}
