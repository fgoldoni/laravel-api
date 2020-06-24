<?php

namespace Modules\Orders\Jobs;

use Darryldecode\Cart\ItemCollection;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Orders\Repositories\Contracts\OrdersRepository;
use Modules\Tickets\Repositories\Contracts\TicketsRepository;

class OrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private $item;
    /**
     * @var int
     */
    private $transactionId;
    /**
     * @var int
     */
    private $userId;
    /**
     * @var float
     */
    private $fee;

    public function __construct(ItemCollection $item, int $transactionId, int $userId, float $fee)
    {
        $this->item = $item;
        $this->queue = 'transaction';
        $this->transactionId = $transactionId;
        $this->userId = $userId;
        $this->fee = $fee;
    }


    public function handle()
    {
        app()->make(OrdersRepository::class)->create(
            [
                'name'           => $this->item->name,
                'price'          => $this->item->price,
                'quantity'       => $this->item->quantity,
                'subtotal'       => $this->item->getPriceSum(),
                'total'          => $this->item->getPriceSumWithConditions(),
                'fee'            => $this->fee,
                'user_id'        => $this->userId,
                'transaction_id' => $this->transactionId,
                'ticket_id'      => $this->item->id,
                'event_id'       => $this->item->associatedModel->event_id,
            ]
        );

        app()->make(TicketsRepository::class)->updateQuantity($this->item->id, $this->item->quantity);
    }
}
