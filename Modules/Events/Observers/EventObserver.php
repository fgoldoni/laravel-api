<?php

namespace Modules\Events\Observers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Modules\Events\Entities\Event;

/**
 * Class EventsObserver.
 */
class EventObserver
{
    public function creating(Event $event)
    {
        if (Auth::check()) {
            abort_if(Auth::user()->cannot('create', $event), 403, 'Forbidden. The user is authenticated, but does not have the permissions to perform an action. Please contact your Support');
        }
    }

    public function updating(Event $event)
    {
        abort_if(Auth::user()->cannot('update', $event), 403, 'Forbidden. The user is authenticated, but does not have the permissions to perform an action. Please contact your Support');
    }

    public function deleting(Event $event)
    {
        abort_if(Auth::user()->cannot('delete', $event), 403, 'Forbidden. The user is authenticated, but does not have the permissions to perform an action. Please contact your Support');
    }

    public function restoring(Event $event)
    {
        abort_if(Auth::user()->cannot('restore', User::class), 403, 'Forbidden. The user is authenticated, but does not have the permissions to perform an action. Please contact your Support');
    }

    public function forceDeleted(Event $event)
    {
        abort_if(Auth::user()->cannot('forceDelete', User::class), 403, 'Forbidden. The user is authenticated, but does not have the permissions to perform an action. Please contact your Support');
    }
}
