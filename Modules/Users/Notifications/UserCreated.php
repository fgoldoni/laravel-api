<?php

namespace Modules\Users\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserCreated extends Notification
{
    use Queueable;

    /**
     * @var \App\User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'id'         => $this->user->id,
            'subject'    => 'User has been successfully created',
            'from'       => $this->user->full_name,
            'url'        => '#',
            'content'    => 'User has been successfully created',
            'image'      => '#',
            'created_at' => $this->user->created_at->format('c')
        ];
    }
}
