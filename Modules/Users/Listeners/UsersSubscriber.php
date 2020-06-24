<?php

namespace Modules\Users\Listeners;

use App\Flag;
use App\User;
use Illuminate\Support\Facades\Notification;
use Modules\Users\Notifications\UserCreated;
use Modules\Users\Notifications\UserDeleted;
use Modules\Users\Notifications\UserRestored;

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
        $events->listen('eloquent.deleting: ' . User::class, [$this, 'handleUserDeleted']);
        $events->listen('eloquent.restored: ' . User::class, [$this, 'handleUserRestored']);
    }

    public function handleUserCreated(User $user)
    {
        $users = $this->getUserByRole(Flag::ROLE_ADMIN);

        Notification::send($users, new UserCreated($user));
    }

    public function handleUserDeleted(User $user)
    {
        $users = $this->getUserByRole(Flag::ROLE_ADMIN);

        Notification::send($users, new UserDeleted($user));
    }

    public function handleUserRestored(User $user)
    {
        $users = $this->getUserByRole(Flag::ROLE_ADMIN);

        Notification::send($users, new UserRestored($user));
    }

    public function getUserByRole(string $role)
    {
        return User::role($role)->get();
    }
}
