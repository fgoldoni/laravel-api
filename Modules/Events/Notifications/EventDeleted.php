<?php

namespace Modules\Events\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Modules\Events\Entities\Event;

class EventDeleted extends Notification
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
        if ($this->event->isForceDeleting()) {
            return [
                'title'    => 'Delete Event Permanently',
                'msg'      => 'Event ' . $this->event->full_name . ' has been deleted permanently by ' . Auth::user()->full_name,
                'url'      => '#',
                'icon'     => 'InfoIcon',
                'time'     => $this->event->created_at->format('c'),
                'category' => 'danger',
            ];
        }

        return [
            'title'    => 'Delete Event',
            'msg'      => 'Event ' . $this->event->full_name . ' has been Deleted by ' . Auth::user()->full_name,
            'url'      => '#',
            'icon'     => 'InfoIcon',
            'time'     => $this->event->created_at->format('c'),
            'category' => 'warning',
        ];
    }
}
