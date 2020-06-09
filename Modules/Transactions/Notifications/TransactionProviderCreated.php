<?php

namespace Modules\Transactions\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Transactions\Entities\Transaction;

class TransactionProviderCreated extends Notification
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

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', 'https://laravel.com')
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
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
