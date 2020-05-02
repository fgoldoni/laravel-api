<?php

namespace Modules\Dashboard\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->using(DashboardUser::class)
            ->withPivot([
                'x',
                'y',
                'w',
                'h',
                'i',
            ]);
    }
}
