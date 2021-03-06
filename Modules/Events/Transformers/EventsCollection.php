<?php

namespace Modules\Events\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class EventsCollection extends JsonResource
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
            'quantity'         => $this->getQuantity(),
            'sale'             => $this->getSale(),
            'rate'             => $this->getRate(),
            'attachments'      => $this->getAttachments(),
            'categories'       => $this->getCategories(),
            'tags'             => $this->getTags(),
            'user'             => [
                'full_name' => $this->user->full_name,
            ],
            'created_at' => $this->created_at,
            'deleted_at' => $this->deleted_at,
            'domain'     => 'http://' . $this->slug . '.' . env('EVENT_DOMAIN', 'sell-first.com'),
            'video'      => $this->video,
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

    private function getQuantity()
    {
        return $this->tickets->sum('quantity');
    }

    private function getSale()
    {
        return $this->tickets->sum('sale');
    }

    private function getRate()
    {
        $q = $this->getQuantity() + $this->getSale();
        $d = 100 * $this->getSale();

        if ($q > 0) {
            return number_format($d / $q, 2);
        }

        return 0;
    }
}
