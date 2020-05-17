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
    private $providerId;

    private $eventTitle;

    public function model()
    {
        return Transaction::class;
    }

    private function getDescription($item)
    {
        $this->eventTitle = $item->attributes['title'];

        $list[] = $item->attributes['title'] . ' ( '. $item->quantity . 'X' . $item->name . ' )';
    }

    private function getDomain($item)
    {
        return 'http://' . $item->attributes->slug . '.' . env('EVENT_DOMAIN', 'sell-first.com');
    }

    private function getTmpItem($item)
    {
        $this->providerId = $item->associatedModel->user_id;

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
        $domain = '#0';
        $items = [];

        foreach ($cart['items'] as $item) {
            $this->getDescription($item);
            $items[] = $this->getTmpItem($item);
            $domain = $this->getDomain($item);
        }

        $description = implode(' | ', $list);

        return $this->resolveModel()->create([
            'gateway'             => $charges['source']['brand'],
            'name'                => $this->eventTitle,
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
            'metadata'    => $charges['metadata'],
            'customer_id' => $userId,
            'provider_id' => $this->providerId,
            'parent_id'   => null,
            'domain'      => $domain
        ]);
    }

    public function makePaypalTransaction(array $charges, $cart, int $userId)
    {
        $list = [];
        $items = [];
        $domain = '#0';

        foreach ($cart['items'] as $item) {
            $list[] = $this->getDescription($item);
            $items[] = $this->getTmpItem($item);
            $domain = $this->getDomain($item);
        }
        $description = implode(' | ', $list);

        return $this->resolveModel()->create([
            'gateway'             => 'Paypal',
            'name'                => $this->eventTitle,
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
            'metadata'    => $this->getMetadata($charges),
            'customer_id' => $userId,
            'provider_id' => $this->providerId,
            'parent_id'   => null,
            'domain'      => $domain
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
