<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use SoftDeletes;
    use LogsActivity;

    protected $dates = ['deleted_at'];

    public $appends = ['full_name', 'all_permissions'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'api_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'api_token', 'remember_token', 'pivot',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static $logOnlyDirty = true;

    protected static $logAttributes = [
        'first_name',
        'last_name',
        'email'
    ];

    protected static $submitEmptyLogs = false;

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        return  ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

    public function getAllPermissionsAttribute()
    {
        $permissions = [];

        foreach (Permission::all() as $permission) {
            if (Auth::user()->can($permission->name)) {
                $permissions[] = $permission->name;
            }
        }

        return $permissions;
    }
}
