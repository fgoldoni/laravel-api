<?php

/**
 * Created by PhpStorm.
 * User: Goldoni
 * Date: 10/11/2018
 * Time: 02:14.
 */

namespace Modules\Tags\Traits;

use Modules\Tags\Entities\Tag;

/**
 * Class TaggableTrait.
 */
trait TaggableTrait
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function saveTags($tags)
    {
        $tags = array_filter(array_unique(array_map(function ($item) {
            return trim($item);
        }, $tags)), function ($item) {
            return !empty($item);
        });
        $persistedTags = Tag::whereIn('name', $tags)->get();
        $tagsToCreate = array_diff($tags, $persistedTags->pluck('name')->all());
        $tagsToCreate = array_map(function ($tag) {
            return ['name' => $tag, 'slug' => str_slug($tag)];
        }, $tagsToCreate);

        $createdTags = $this->tags()->createMany($tagsToCreate);
        $persistedTags = $persistedTags->merge($createdTags);
        $this->tags()->sync($persistedTags);

        return $this;
    }
}
