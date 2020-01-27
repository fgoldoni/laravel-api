<?php

namespace Modules\Carts\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
    private $userId;

    public function __construct(array $items, int $userId)
    {
        $this->items = $items;
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
            $orderList = app('orderlist');

            $orderList->session($this->userId)->add([
                'id'                     => $item->id,
                'name'                   => $item->name,
                'price'                  => $item->price,
                'quantity'               => $item->quantity,
                'attributes'             => $item->attributes,
                'associatedModel'        => $item->associatedModel
            ]);
        }
    }
}
