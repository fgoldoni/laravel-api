<?php

namespace Modules\Transactions\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Modules\Tickets\Entities\Ticket;
use Modules\Transactions\Entities\Transaction;
use Modules\Transactions\Repositories\Eloquent\EloquentTransactionsRepository;

class TransactionJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    /**
     * @var array
     */
    private $items;
    /**
     * @var array
     */
    private $charges;
    /**
     * @var int
     */
    private $parentId;
    /**
     * @var int
     */
    private $userId;

    public function __construct(array $charges, array $items, int $userId, int $parentId)
    {
        $this->charges = $charges;
        $this->items = $items;
        $this->parentId = $parentId;
        $this->queue = 'transaction';
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->items as $item) {
            $description = $this->getDescription($item);
            $tmpItem = $this->getTmpItem($item);
            $total = number_format($item->quantity * $item->price, 2, '.', ',');
            $transaction = $this->makeTransaction($item->price, $description, [$tmpItem], $item->quantity, $total, $total, $item->associatedModel->user_id, $item->associatedModel->user_id);
            $this->updateTicket($item);
            $this->makeOrder($item, $transaction);
        }
    }

    private function getDescription($item)
    {
        return $item->id . ':' . $item->name;
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

    private function makeTransaction($price, string $description, array $items, int $quantity, $subtotal, $total, int $userId, int $parentId = null)
    {
        return app()->make(EloquentTransactionsRepository::class)->create([
            'gateway'             => $this->charges['source']['brand'],
            'transaction_key'     => $this->charges['id'],
            'transaction_balance' => $this->charges['balance_transaction'],
            'status'              => $this->charges['status'],
            'price'               => $price,
            'description'         => $description,
            'last4'               => $this->charges['source']['last4'],
            'country'             => $this->charges['source']['country'],
            'created'             => Carbon::createFromTimestamp($this->charges['created'])->toDateTimeString(),
            'detail'              => [
                'items'    => $items,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
                'total'    => $total,
            ],
            'metadata'  => $this->charges['metadata'],
            'user_id'   => $userId,
            'parent_id' => $this->parentId
        ]);
    }

    private function updateTicket($item)
    {
        $ticket = Ticket::find($item->id);

        $ticket->quantity = (int) $ticket->quantity - (int) $item->quantity;
        $ticket->sale = (int) $ticket->sale + (int) $item->quantity;

        return $ticket->save();
    }

    private function makeOrder($item, Transaction $transaction)
    {
        $orderList = app('orderlist');

        $orderList->session($this->userId)->add([
            'id'                     => $transaction->id,
            'name'                   => $item->name,
            'price'                  => $item->price,
            'quantity'               => $item->quantity,
            'attributes'             => $item->attributes,
            'associatedModel'        => $item->associatedModel
        ]);
    }
}
