<?php

namespace Modules\Events\Listeners;

use App\Flag;
use App\User;
use Illuminate\Support\Facades\Notification;
use Modules\Events\Entities\Event;
use Modules\Events\Notifications\EventCreated;
use Modules\Events\Notifications\EventDeleted;
use Modules\Events\Notifications\EventRestored;

class EventsSubscriber
{
    /**
     * @var \Modules\Events\Entities\Event
     */
    private $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * @param $events
     */
    public function subscribe($events)
    {
        $events->listen('eloquent.created: ' . Event::class, [$this, 'handleEventCreated']);
        $events->listen('eloquent.deleting: ' . Event::class, [$this, 'handleEventDeleted']);
        $events->listen('eloquent.restored: ' . Event::class, [$this, 'handleEventRestored']);
    }

    public function handleEventCreated(Event $event)
    {
        $users = $this->getUserByRole(Flag::ROLE_ADMIN);

        Notification::send($users, new EventCreated($event));
    }

    public function handleEventDeleted(Event $event)
    {
        $users = $this->getUserByRole(Flag::ROLE_ADMIN);

        Notification::send($users, new EventDeleted($event));
    }

    public function handleEventRestored(Event $event)
    {
        $users = $this->getUserByRole(Flag::ROLE_ADMIN);

        Notification::send($users, new EventRestored($event));
    }

    public function getUserByRole(string $role)
    {
        return User::role($role)->get();
    }
}
