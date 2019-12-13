<?php

namespace Modules\Events\Policies;

use App\Flag;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Events\Entities\Event;

class EventPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->hasPermissionTo(Flag::PERMISSION_ADMIN)) {
            return true;
        }
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('CREATE_EVENT');
    }

    public function update(User $user, Event $model)
    {
        return $user->hasPermissionTo('UPDATE_EVENT') && ((int) $user->id === (int) $model->user_id);
    }

    public function delete(User $user, Event $model)
    {
        return $user->hasPermissionTo('DELETE_EVENT') && ((int) $user->id === (int) $model->user_id);
    }

    public function forceDelete(User $user)
    {
        return $user->hasPermissionTo('FORCE_DELETE_EVENT');
    }

    public function restore(User $user)
    {
        return $user->hasPermissionTo('RESTORE_EVENT');
    }
}
