<?php

namespace Modules\Dashboard\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class DashboardUser extends Pivot
{
    protected $guarded = [];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;
}
