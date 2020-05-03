<?php

namespace Modules\Carts\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Orders\Repositories\Contracts\OrdersRepository;

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
                    'customer_id' => $this->userId,
                    'provider_id' => $item->associatedModel->user_id,
                    'transaction_id' => $this->transactionId,
                    'ticket_id' => $item->id,
                    'event_id' => $item->associatedModel->event_id,
                ]
            );
        }
    }
}
