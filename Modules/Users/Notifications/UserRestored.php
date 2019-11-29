<?php

namespace Modules\Users\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class UserRestored extends Notification
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
            'title'    => 'Restore User',
            'msg'      => 'User ' . $this->user->full_name . ' has been successfully restored by ' . Auth::user()->full_name,
            'url'      => '#',
            'icon'     => 'UserCheckIcon',
            'time'     => $this->user->created_at->format('c'),
            'category' => 'primary',
        ];
    }
}
