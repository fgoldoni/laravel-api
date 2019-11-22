<?php

namespace Modules\Roles\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Role extends \Spatie\Permission\Models\Role
{
    use SoftDeletes;
    use LogsActivity;

    protected $dates = ['deleted_at'];

    protected static $logOnlyDirty = true;

    protected static $logAttributes = [
        'name'
    ];
}
