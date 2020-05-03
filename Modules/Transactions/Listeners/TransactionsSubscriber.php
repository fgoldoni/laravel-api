<?php

namespace Modules\Transactions\Listeners;

use App\Flag;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Modules\Transactions\Entities\Transaction;
use Modules\Transactions\Notifications\TransactionCreated;
use Modules\Transactions\Notifications\TransactionDeleted;
use Modules\Transactions\Notifications\TransactionRestored;

class TransactionsSubscriber
{
    /**
     * @var \Modules\Transactions\Entities\Transaction
     */
    private $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @param $events
     */
    public function subscribe($events)
    {
        $events->listen('eloquent.created: ' . Transaction::class, [$this, 'handleTransactionCreated']);
        $events->listen('eloquent.deleting: ' . Transaction::class, [$this, 'handleTransactionDeleted']);
        $events->listen('eloquent.restored: ' . Transaction::class, [$this, 'handleTransactionRestored']);
    }

    public function handleTransactionCreated(Transaction $transaction)
    {
        $users = $this->getUserByRole(Flag::ROLE_ADMIN);
        $users->push($this->getUserById(Auth::id()));
        $users->push($this->getUserById($transaction->detail['items'][0]['user_id']));

        Notification::send($users, new TransactionCreated($transaction));
    }

    public function handleTransactionDeleted(Transaction $transaction)
    {
        $users = $this->getUserByRole(Flag::ROLE_ADMIN);

        Notification::send($users, new TransactionDeleted($transaction));
    }

    public function handleTransactionRestored(Transaction $transaction)
    {
        $users = $this->getUserByRole(Flag::ROLE_ADMIN);

        Notification::send($users, new TransactionRestored($transaction));
    }

    public function getUserByRole(string $role)
    {
        return User::where('users.id', '!=', Auth::id())->role($role)->get();
    }

    public function getUserById(string $id)
    {
        return User::find($id);
    }
}
