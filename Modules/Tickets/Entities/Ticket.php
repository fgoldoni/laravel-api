<?php

namespace Modules\Tickets\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Events\Entities\Event;
use Rinvex\Categories\Traits\Categorizable;

class Ticket extends Model
{
    use Categorizable;

    protected $guarded = [];

    protected $casts = [
        'free'    => 'boolean',
        'online'  => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
