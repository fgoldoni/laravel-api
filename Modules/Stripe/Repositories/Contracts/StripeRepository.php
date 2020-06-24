<?php

namespace Modules\Stripe\Repositories\Contracts;

use Modules\Transactions\Repositories\Contracts\TransactionsRepository;

interface StripeRepository
{
    public function customers(string $name, string $email, string $mobile, string $stripeToken);

    public function charges($customer);

    public function intent();

    public function make(array $charges, TransactionsRepository $transactions);
}
