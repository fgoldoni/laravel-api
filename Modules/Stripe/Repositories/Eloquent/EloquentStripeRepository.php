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
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Modules\Carts\Jobs\OrderJob;
use Modules\Carts\Repositories\Contracts\CartsRepository;
use Modules\Carts\Repositories\Eloquent\EloquentCartsRepository;
use Modules\Stripe\Repositories\Contracts\StripeRepository;
use Modules\Transactions\Entities\Transaction;
use Modules\Transactions\Jobs\TransactionJob;

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
     * @var array
     */
    private $items = [];
    /**
     * @var \Modules\Carts\Repositories\Contracts\CartsRepository
     */
    private $cart;

    public function __construct(Arr $arr, AuthManager $auth, CartsRepository $cart)
    {
        parent::__construct($arr);

        $this->stripe = Stripe::make(env('STRIPE_SECRET', Flag::PERMISSION_ADMIN));
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
            $list[] = $item->id . ':' . $item->name;
        }

        $description = implode(' | ', $list);

        return $this->stripe->customers()->create([
            'source'      => $stripeToken,
            'description' => $description,
            'email'       => $email,
            'metadata'    => [
                'id'    => $this->auth->guard('api')->user()->id,
                'email' => $email,
                'mobile' => $mobile,
                'name'  => $name
            ]
        ]);
    }

    public function charges($customer)
    {
        $list = [];
        $cart = $this->cart->details();

        foreach ($cart['items'] as $item) {
            $list[] = $item->id . ':' . $item->name;
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

    public function make(array $charges)
    {
        $cart = $this->cart->details();

        $transaction = $this->makeTransaction($charges, $cart, $this->auth->guard('api')->user()->id);

        TransactionJob::dispatch($charges, $cart['items'], $this->auth->guard('api')->user()->id, $transaction->id);

        //OrderJob::dispatch($cart['items'], $this->auth->guard('api')->user()->id);

        app()->make(EloquentCartsRepository::class)->clear();

        return $transaction;
    }

    private function makeTransaction(array $charges, $cart, int $userId)
    {
        $list = [];
        $items = [];

        foreach ($cart['items'] as $item) {
            $list[] = $this->getDescription($item);
            $items[] = $this->getTmpItem($item);
        }

        $description = implode(' | ', $list);

        return $this->resolveModel()->create([
            'gateway'             => $charges['source']['brand'],
            'transaction_key'     => $charges['id'],
            'transaction_balance' => $charges['balance_transaction'],
            'status'              => $charges['status'],
            'price'               => $cart['sub_total'],
            'description'         => $description,
            'last4'               => $charges['source']['last4'],
            'country'             => $charges['source']['country'],
            'created'             => Carbon::createFromTimestamp($charges['created'])->toDateTimeString(),
            'detail'              => [
                'items'                     => $items,
                'quantity'                  => $cart['total_quantity'],
                'subtotal'                  => $cart['sub_total'],
                'total'                     => $cart['total'],
                'cart_sub_total_conditions' => $cart['cart_sub_total_conditions'],
                'cart_total_conditions'     => $cart['cart_total_conditions'],
            ],
            'metadata'  => $charges['metadata'],
            'user_id'   => $userId,
            'parent_id' => null
        ]);
    }

    private function getDescription($item)
    {
        return $item->id . ':' . $item->name;
    }

    private function getTmpItem($item)
    {
        return [
            'id'       => $item->id,
            'name'     => $item->name,
            'price'    => $item->price,
            'quantity' => $item->quantity,
            'user_id'  => $item->associatedModel->user_id,
            'attributes'  => $item->attributes,
            'associatedModel'  => $item->associatedModel
        ];
    }
}
