<?php

namespace Modules\Tickets\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class TicketsCollection extends JsonResource
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
            'offer_1'              => Str::upper($this->offer_1),
            'offer_2'              => Str::upper($this->offer_2),
            'offer_3'              => Str::upper($this->offer_3),
            'offer_4'              => Str::upper($this->offer_4),
            'quantity'             => $this->quantity,
            'sale'                 => $this->sale,
            'price'                => $this->price,
            'online'               => $this->online,
            'position'             => $this->position,
            'category'             => $this->getCategories(),
            'category_id'          => $this->getCategoryId(),
        ];
    }

    private function getCategories()
    {
        $category = $this->categories()->first();

        if (null !== $category) {
            return $category->name;
        }

        return 'Gold';
    }

    private function getCategoryId()
    {
        $category = $this->categories()->first();

        if (null !== $category) {
            return $category->id;
        }

        return 13;
    }
}
