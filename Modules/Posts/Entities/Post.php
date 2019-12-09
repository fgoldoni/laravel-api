<?php

namespace Modules\Posts\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Attachments\Traits\AttachableTrait;
use Rinvex\Categories\Traits\Categorizable;
use Spatie\Activitylog\Traits\LogsActivity;

class Post extends Model
{
    use SoftDeletes;
    use LogsActivity;
    use Categorizable;
    use AttachableTrait;

    protected $fillable = ['name', 'content', 'online', 'user_id'];

    protected static $logAttributes = ['name', 'content', 'online'];

    protected static $logOnlyDirty = true;

    protected $dates = ['deleted_at'];

    protected $casts = [
        'online' => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublished($query, $published)
    {
        $query->where('online', $published);

        return $query;
    }
}
