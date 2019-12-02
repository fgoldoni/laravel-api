<?php

namespace Modules\Attachments\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Modules\Attachments\Entities\Attachment;

class AttachmentDeleted extends Notification
{
    use Queueable;
    /**
     * @var \Modules\Attachments\Entities\Attachment
     */
    private $attachment;

    public function __construct(Attachment $attachment)
    {
        $this->attachment = $attachment;
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
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title'    => 'Delete Attachment',
            'msg'      => 'Attachment: ' . $this->attachment->id . ' has been successfully deleted',
            'url'      => '#',
            'icon'     => 'FileMinusIcon',
            'time'     => $this->attachment->created_at->format('c'),
            'category' => 'warning',
        ];
    }
}
