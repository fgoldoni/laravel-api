<?php

namespace Modules\Users\Listeners;

use App\Flag;
use App\User;
use Illuminate\Support\Facades\Notification;
use Modules\Users\Notifications\UserCreated;

class UsersSubscriber
{
    /**
     * @var \App\User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param $events
     */
    public function subscribe($events)
    {
        $events->listen('eloquent.created: ' . User::class, [$this, 'handleUserCreated']);
    }

    public function handleUserCreated(User $user)
    {
        $users = User::role(Flag::ROLE_ADMIN)->get();

        Notification::send($users, new UserCreated($user));
    }
}
