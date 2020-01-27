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

    public function customers(string $name, string $email, string $stripeToken)
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
        $list = [];
        $items = [];
        $cart = $this->cart->details();

        foreach ($cart['items'] as $item) {
            $list[] = $this->getDescription($item);
            $items[] = $this->getTmpItem($item);
        }

        $description = implode(' | ', $list);

        $transaction = $this->makeTransaction($charges, $cart['total'], $description, $items, $cart['total_quantity'], $cart['sub_total'], $cart['total'], $this->auth->guard('api')->user()->id);

        TransactionJob::dispatch($charges, $cart['items'], $this->auth->guard('api')->user()->id);
        OrderJob::dispatch($cart['items'], $this->auth->guard('api')->user()->id);

        return $transaction;
    }

    private function makeTransaction(array $charges, $price, string $description, array $items, int $quantity, $subtotal, $total, int $userId, int $parentId = null)
    {
        return $this->resolveModel()->create([
            'gateway'             => $charges['source']['brand'],
            'transaction_key'     => $charges['id'],
            'transaction_balance' => $charges['balance_transaction'],
            'status'              => $charges['status'],
            'price'               => $price,
            'description'         => $description,
            'last4'               => $charges['source']['last4'],
            'country'             => $charges['source']['country'],
            'created'             => Carbon::createFromTimestamp($charges['created'])->toDateTimeString(),
            'detail'              => [
                'items'    => $items,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
                'total'    => $total,
            ],
            'metadata'  => $charges['metadata'],
            'user_id'   => $userId,
            'parent_id' => $parentId
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
        ];
    }
}
