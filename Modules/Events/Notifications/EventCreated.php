<?php

namespace Modules\Events\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Modules\Events\Entities\Event;

class EventCreated extends Notification
{
    use Queueable;

    /**
     * @var \Modules\Events\Entities\Event
     */
    private $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
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

    public function toArray($notifiable)
    {
        return [
            'title'    => 'New Event',
            'msg'      => 'User ' . $this->event->title . ' has been successfully created',
            'url'      => '#',
            'icon'     => 'GiftIcon',
            'time'     => $this->event->created_at->format('c'),
            'category' => 'primary',
        ];
    }
}
