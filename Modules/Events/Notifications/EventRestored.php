<?php

namespace Modules\Events\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Modules\Events\Entities\Event;

class EventRestored extends Notification
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

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title'    => 'Restore Event',
            'msg'      => 'Event ' . $this->event->title . ' has been successfully restored',
            'url'      => '#',
            'icon'     => 'CheckCircleIcon',
            'time'     => $this->event->created_at->format('c'),
            'category' => 'primary',
        ];
    }
}
