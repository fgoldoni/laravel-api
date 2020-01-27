<?php

namespace Modules\Transactions\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Modules\Transactions\Entities\Transaction;

class TransactionCreated extends Notification implements ShouldQueue
{
    use Queueable;
    /**
     * @var \Modules\Transactions\Entities\Transaction
     */
    private $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->queue = 'transaction';
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title'    => 'New Transaction : ' . $this->transaction->id . ' ( € ' . $this->transaction->detail['total'] . ' )',
            'msg'      => 'Transaction has been successfully created by ' . $this->transaction->metadata['name'],
            'url'      => '#',
            'icon'     => 'RefreshCcwIcon',
            'time'     => $this->transaction->created_at->format('c'),
            'category' => 'primary',
        ];
    }
}
