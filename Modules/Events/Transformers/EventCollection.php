<?php

namespace Modules\Events\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class EventCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'title'            => $this->title,
            'slug'             => $this->slug,
            'description'      => $this->description,
            'content'          => $this->content,
            'address'          => $this->address,
            'start'            => $this->start,
            'end'              => $this->end,
            'url'              => $this->url,
            'color'            => $this->color,
            'all_day'          => $this->all_day,
            'online'           => $this->online,
            'attachments'      => $this->getAttachments(),
            'categories'       => $this->getCategories(),
            'tags'             => $this->getTags(),
            'user'             => [
                'full_name' => $this->user->full_name,
            ],
            'created_at' => $this->created_at,
            'deleted_at' => $this->deleted_at,
        ];
    }

    private function getCategories()
    {
        return $this->categories()->get()->map(function ($category) {
            return [
                'id'   => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
            ];
        });
    }

    private function getAttachments()
    {
        return $this->attachments()->get()->map(function ($category) {
            return [
                'url' => $category->url
            ];
        });
    }

    private function getTags()
    {
        return $this->tags()->get()->map(function ($tag) {
            return [
                'name' => $tag->name
            ];
        });
    }
}
