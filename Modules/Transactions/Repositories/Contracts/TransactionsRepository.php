<?php

namespace Modules\Transactions\Repositories\Contracts;

interface TransactionsRepository
{
    public function makeCardTransaction(array $charges, $cart, int $userId);
    public function makePaypalTransaction(array $charges, $cart, int $userId);
}
