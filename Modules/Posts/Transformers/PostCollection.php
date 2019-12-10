<?php

namespace Modules\Posts\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PostCollection extends JsonResource
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
            'id'          => $this->id,
            'name'        => $this->name,
            'content'     => $this->content,
            'online'      => $this->online,
            'attachments' => $this->getAttachments(),
            'categories'  => $this->getCategories(),
            'user'        => [
                'full_name' => $this->user->full_name,
            ],
            'created_at' => $this->created_at,
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
}
