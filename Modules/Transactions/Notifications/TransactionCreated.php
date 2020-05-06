<?php

namespace Modules\Transactions\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
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
        return ['mail', 'database'];
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

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject('['. env('APP_NAME', 'SellFirst Portal') .'] Rechnung Nr: '. $this->transaction->id .' ist verfügbar ' )
            ->view('transactions::emails.created', ['homeUrl' => '#0', 'url' => '#0']);
    }
}
