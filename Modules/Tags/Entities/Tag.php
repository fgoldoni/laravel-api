<?php

namespace Modules\Tags\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Posts\Entities\Post;

class Tag extends Model
{
    public $guarded = [];

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function post()
    {
        return $this->belongsToMany(Post::class);
    }
}
