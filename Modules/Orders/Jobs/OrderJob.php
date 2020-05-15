<?php

namespace Modules\Orders\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Orders\Repositories\Contracts\OrdersRepository;
use Modules\Tickets\Repositories\Contracts\TicketsRepository;

class OrderJob implements ShouldQueue
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
     * @var int
     */
    private $transactionId;
    /**
     * @var int
     */
    private $userId;

    public function __construct(array $items, int $transactionId, int $userId)
    {
        $this->items = $items;
        $this->queue = 'transaction';
        $this->transactionId = $transactionId;
        $this->userId = $userId;
    }


    public function handle()
    {
        foreach ($this->items as $item) {
            app()->make(OrdersRepository::class)->create(
                [
                    'name' => $item->name,
                    'price' => $item->price,
                    'quantity'  => $item->quantity,
                    'user_id' => $this->userId,
                    'transaction_id' => $this->transactionId,
                    'ticket_id' => $item->id,
                    'event_id' => $item->associatedModel->event_id,
                ]
            );
            app()->make(TicketsRepository::class)->updateQuantity($item->id, $item->quantity);
        }
    }
}
