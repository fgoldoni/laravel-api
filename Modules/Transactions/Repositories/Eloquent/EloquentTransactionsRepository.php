<?php

/**
 * Created by PhpStorm.
 * User: goldoni
 * Date: 24.09.18
 * Time: 21:18.
 */

namespace Modules\Transactions\Repositories\Eloquent;

use App\Repositories\RepositoryAbstract;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Transactions\Entities\Transaction;
use Modules\Transactions\Repositories\Contracts\TransactionsRepository;

/**
 * Class EloquentTransactionsRepository.
 */
class EloquentTransactionsRepository extends RepositoryAbstract implements TransactionsRepository
{
    public function model()
    {
        return Transaction::class;
    }

    private function getDescription($item)
    {
        return $item->quantity . 'X' .$item->name;
    }

    private function getTmpItem($item)
    {
        return [
            'id'               => $item->id,
            'name'             => $item->name,
            'price'            => $item->price,
            'quantity'         => $item->quantity,
            'user_id'          => $item->associatedModel->user_id,
            'attributes'       => $item->attributes,
            'associatedModel'  => $item->associatedModel
        ];
    }

    public function makeCardTransaction(array $charges, $cart, int $userId)
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

    public function makePaypalTransaction(array $charges, $cart, int $userId)
    {
        $list = [];
        $items = [];

        foreach ($cart['items'] as $item) {
            $list[] = $this->getDescription($item);
            $items[] = $this->getTmpItem($item);
        }
        $description = implode(' | ', $list);

        return $this->resolveModel()->create([
            'gateway'             => 'Paypal',
            'transaction_key'     => $charges['paymentId'],
            'status'              => 'succeeded',
            'price'               => $cart['total'],
            'description'         => $description,
            'created'             => Carbon::now()->toDateTimeString(),
            'detail'              => [
                'items'                     => $items,
                'quantity'                  => $cart['total_quantity'],
                'subtotal'                  => $cart['sub_total'],
                'total'                     => $cart['total'],
                'cart_sub_total_conditions' => $cart['cart_sub_total_conditions'],
                'cart_total_conditions'     => $cart['cart_total_conditions'],
            ],
            'metadata'  => $this->getMetadata($charges),
            'user_id'   => $userId,
            'parent_id' => null
        ]);
    }

    private function getMetadata($charges)
    {
        return [
            'id' => Auth::user()->id,
            'email' => Auth::user()->email,
            'mobile' => Auth::user()->mobile,
            'name' => Auth::user()->full_name,
            'paymentId' => $charges['paymentId'],
            'PayerID' => $charges['PayerID']
        ];
    }
}
