<?php

namespace Modules\Stripe\Repositories\Contracts;

interface StripeRepository
{
    public function customers(string $name, string $email, string $stripeToken);

    public function charges($customer);

    public function intent();

    public function make(array $charges);
}
