<?php

namespace Modules\Events\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Laravel\Scout\Searchable;
use Modules\Attachments\Traits\AttachableTrait;
use Modules\Tags\Traits\TaggableTrait;
use Nicolaslopezj\Searchable\SearchableTrait;
use Rinvex\Categories\Traits\Categorizable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use willvincent\Rateable\Rateable;

class Event extends Model
{
    use SoftDeletes;
    use LogsActivity;
    use SearchableTrait;
    use AttachableTrait;
    use TaggableTrait;
    use Categorizable;
    use Rateable;
    use HasSlug;
    use Searchable;

    public $guarded = [];

    protected $date = ['start', 'end', 'deleted_at', 'reminder_at'];

    protected static $logAttributes = ['title', 'description', 'content', 'start', 'end', 'url', 'address', 'reminder_at'];

    protected static $logOnlyDirty = true;

    protected $casts = [
        'all_day' => 'boolean',
        'online'  => 'boolean',
    ];

    public $appends = ['old', 'average_rating', 'user_average_rating'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attendees()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function eventable()
    {
        return $this->morphTo();
    }

    public function scopePublished($query, $published)
    {
        $query->where('online', $published);

        return $query;
    }

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        /*
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'events.id'                => 10,
            'events.title'             => 10,
            'events.start'             => 10,
            'users.first_name'         => 10,
            'users.last_name'          => 10,
        ],
        'joins' => [
            'users' => ['events.user_id', 'users.id'],
        ],
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public function getOldAttribute()
    {
        return $this->start < Carbon::now();
    }

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'events_index';
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'id'               => $this->id,
            'title'            => $this->title,
            'slug'             => $this->slug,
            'description'      => $this->description,
            'content'          => $this->content,
            'city'             => $this->city,
            'contact_phone'    => $this->contact_phone,
            'contact_email'    => $this->contact_email,
            'contact_name'     => $this->contact_name,
            'address'          => $this->address,
            'start'            => $this->start,
            'start_timestamp'  => Carbon::parse($this->start)->timestamp,
            'end'              => $this->end,
            'url'              => $this->url,
            'color'            => $this->color,
            'all_day'          => $this->all_day,
            'online'           => $this->online,
            'image'            => $this->getImage(),
            'categories'       => $this->getCategories(),
            'tags'             => $this->getTags(),
            'rating'           => $this->getRating(),
            'price'            => $this->getPrice(),
            'owner'            => $this->user->full_name,
            'created_at'       => $this->created_at,
            'deleted_at'       => $this->deleted_at,
        ];
    }

    private function getCategories()
    {
        return $this->categories()->get()->map(function ($category) {
            return [$category->name];
        });
    }

    private function getImage()
    {
        if ($image = $this->attachments()->where('type', 'cover')->latest()->first()) {
            return $image->url;
        }

        return asset('storage/uploads/events/1577545968.jpg');
    }

    private function getTags()
    {
        return $this->tags()->get()->map(function ($tag) {
            return [
                'name' => $tag->name
            ];
        });
    }

    private function getRating()
    {
        return random_int(1, 5);
    }

    private function getPrice()
    {
        return random_int(1, 1000);
    }

    public function visits()
    {
        return visits($this);
    }
}
