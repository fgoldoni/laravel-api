<?php

namespace Modules\Orders\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Events\Entities\Event;
use Modules\Tickets\Entities\Ticket;
use Modules\Transactions\Entities\Transaction;
use Spatie\Activitylog\Traits\LogsActivity;

class Order extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $guarded = [];

    protected $dates = ['deleted_at'];

    protected static $logAttributes = ['name', 'price', 'qty', 'tax'];

    protected static $logOnlyDirty = true;

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
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
