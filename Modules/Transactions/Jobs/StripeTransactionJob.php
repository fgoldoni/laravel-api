<?php

namespace Modules\Transactions\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Carts\Jobs\OrderJob;

class StripeTransactionJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private $transactions;
    /**
     * @var array
     */
    private $charges;

    private $cart;
    /**
     * @var int
     */
    private $userId;

    public function __construct($transactions, array $charges, $cart, int $userId)
    {
        $this->queue = 'transaction';
        $this->transactions = $transactions;
        $this->charges = $charges;
        $this->cart = $cart;
        $this->userId = $userId;
    }

    public function handle()
    {
        $transaction = $this->transactions->makeCardTransaction($this->charges, $this->cart, $this->userId);

        OrderJob::dispatch($this->cart['items'], $transaction->id, $this->userId);
    }
}
