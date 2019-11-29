<?php

namespace Modules\Users\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class UserDeleted extends Notification
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
        if ($this->user->isForceDeleting()) {
            return [
                'title'    => 'Delete User Permanently',
                'msg'      => 'User ' . $this->user->full_name . ' has been deleted permanently by ' . Auth::user()->full_name,
                'url'      => '#',
                'icon'     => 'UserXIcon',
                'time'     => $this->user->created_at->format('c'),
                'category' => 'danger',
            ];
        }

        return [
            'title'    => 'Delete User',
            'msg'      => 'User ' . $this->user->full_name . ' has been Deleted by ' . Auth::user()->full_name,
            'url'      => '#',
            'icon'     => 'UserMinusIcon',
            'time'     => $this->user->created_at->format('c'),
            'category' => 'warning',
        ];
    }
}
