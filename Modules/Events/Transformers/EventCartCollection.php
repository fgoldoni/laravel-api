<?php

namespace Modules\Events\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class EventCartCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'title'            => $this->title,
            'slug'             => $this->slug,
            'description'      => $this->description,
            'content'          => $this->content,
            'address'          => $this->address,
            'city'             => $this->city,
            'start'            => $this->start,
            'end'              => $this->end,
            'url'              => $this->url,
            'image'            => $this->getAttachment(),
            'categories'       => $this->getCategories(),
            'rating'           => random_int(2, 5),
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

    private function getAttachment()
    {
        if ($image = $this->attachments()->where('type', 'cover')->latest()->first()) {
            return $image->url;
        }

        return asset('storage/uploads/events/1577545968.jpg');
    }
}
