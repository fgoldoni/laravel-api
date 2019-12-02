<?php

namespace Modules\Attachments\Listeners;

use App\Flag;
use App\User;
use Illuminate\Support\Facades\Notification;
use Modules\Attachments\Entities\Attachment;
use Modules\Attachments\Notifications\AttachmentCreated;
use Modules\Attachments\Notifications\AttachmentDeleted;

class AttachmentsSubscriber
{
    /**
     * @var \Modules\Attachments\Entities\Attachment
     */
    private $attachment;

    public function __construct(Attachment $attachment)
    {
        $this->attachment = $attachment;
    }

    /**
     * @param $events
     */
    public function subscribe($events)
    {
        $events->listen('eloquent.created: ' . Attachment::class, [$this, 'handleAttachmentCreated']);
        $events->listen('eloquent.deleting: ' . Attachment::class, [$this, 'handleAttachmentDeleted']);
    }

    public function handleAttachmentCreated(Attachment $attachment)
    {
        $users = $this->getUserByRole(Flag::ROLE_ADMIN);

        Notification::send($users, new AttachmentCreated($attachment));
    }

    public function handleAttachmentDeleted(Attachment $attachment)
    {
        $users = $this->getUserByRole(Flag::ROLE_ADMIN);

        Notification::send($users, new AttachmentDeleted($attachment));
    }

    public function getUserByRole(string $role)
    {
        return User::role($role)->get();
    }
}
