<?php

/**
 * Created by PhpStorm.
 * Role: goldoni
 * Date: 24.09.18
 * Time: 21:18.
 */

namespace Modules\Stripe\Repositories\Eloquent;

use App\Flag;
use App\Repositories\RepositoryAbstract;
use Cartalyst\Stripe\Stripe;
use Darryldecode\Cart\Facades\CartFacade;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Modules\Carts\Repositories\Contracts\CartsRepository;
use Modules\Carts\Repositories\Eloquent\EloquentCartsRepository;
use Modules\Orders\Jobs\OrderJob;
use Modules\Stripe\Repositories\Contracts\StripeRepository;
use Modules\Transactions\Entities\Transaction;
use Modules\Transactions\Repositories\Contracts\TransactionsRepository;

/**
 * Class EloquentStripeRepository.
 */
class EloquentStripeRepository extends RepositoryAbstract implements StripeRepository
{
    /**
     * @var \Cartalyst\Stripe\Stripe
     */
    private $stripe;
    /**
     * @var \Illuminate\Auth\AuthManager
     */
    private $auth;

    /**
     * @var \Modules\Carts\Repositories\Contracts\CartsRepository
     */
    private $cart;

    public function __construct(Arr $arr, AuthManager $auth, CartsRepository $cart)
    {
        parent::__construct($arr);

        $this->stripe = Stripe::make(env('STRIPE_SECRET', Flag::STRIPE_SECRET));
        $this->auth = $auth;
        $this->cart = $cart;
    }

    public function model()
    {
        return Transaction::class;
    }

    public function customers(string $name, string $email, string $mobile, string $stripeToken)
    {
        $list = [];
        $cart = $this->cart->details();

        foreach ($cart['items'] as $item) {
            $list[] = $item->attributes->title . ' ( ' . $item->quantity . 'X' . $item->name . ' )';
        }

        $description = implode(' | ', $list);

        return $this->stripe->customers()->create([
            'source'      => $stripeToken,
            'description' => $description,
            'email'       => $email,
            'metadata'    => [
                'id'     => $this->auth->user()->id,
                'email'  => $email,
                'mobile' => $mobile,
                'name'   => $name
            ]
        ]);
    }

    public function charges($customer)
    {
        $list = [];
        $cart = $this->cart->details();

        foreach ($cart['items'] as $item) {
            $list[] = $item->attributes->title . ' ( ' . $item->quantity . 'X' . $item->name . ' )';
        }

        $amount = (float) ($cart['total']);

        $description = implode(' | ', $list);

        return $this->stripe->charges()->create([
            'customer'    => $customer['id'],
            'currency'    => 'eur',
            'amount'      => $amount,
            'description' => $description,
            'metadata'    => $customer['metadata']
        ]);
    }

    public function intent()
    {
        $cart = $this->cart->details();

        $amount = (float) ($cart['total']);

        return $this->stripe->paymentIntents()->create([
            'currency'             => 'eur',
            'amount'               => $amount,
            'setup_future_usage'   => 'off_session',
            'payment_method_types' => ['sepa_debit'],
        ]);
    }

    public function make(array $charges, TransactionsRepository $transactions)
    {
        $cart = $this->cart->details();

        $transaction = $transactions->makeCardTransaction($charges, $cart, $this->auth->user()->id);

        $this->cart->addFee(Flag::FEE);

        $cart = $this->cart->details();

        foreach ($cart['items'] as $item) {
            $fee = 0;

            foreach($item->getConditions() as $condition)
            {
                $fee += $condition->getCalculatedValue($item->getPriceSum());
            }

            OrderJob::dispatch($item, $transaction->id, $this->auth->user()->id, $fee);
        }

        app()->make(EloquentCartsRepository::class)->clear();

        return $transaction;
    }
}
