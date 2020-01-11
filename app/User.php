<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\Attachments\Traits\AttachableTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use HasRoles;
    use SoftDeletes;
    use LogsActivity;
    use AttachableTrait;

    protected $dates = ['deleted_at'];

    public $appends = ['full_name', 'all_permissions'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'api_token', 'email_verified_at',
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
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();

        return $array;
    }

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
            if ($this->can($permission->name)) {
                $permissions[] = $permission->name;
            }
        }

        return $permissions;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
