<?php

namespace Modules\Tickets\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketCollection extends JsonResource
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
            'id'                   => $this->id,
            'name'                 => $this->name,
            'offer_1'              => $this->offer_1,
            'offer_2'              => $this->offer_2,
            'offer_3'              => $this->offer_3,
            'offer_4'              => $this->offer_4,
            'quantity'             => $this->quantity,
            'price'                => $this->price,
            'event_id'             => $this->event_id,
            'online'               => $this->online,
            'position'             => $this->position,
            'categories'           => $this->getCategories(),
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
}
