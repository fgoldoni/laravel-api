<?php

namespace Modules\Tickets\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

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
            'offer_1'              => Str::upper($this->offer_1),
            'offer_2'              => Str::upper($this->offer_2),
            'offer_3'              => Str::upper($this->offer_3),
            'offer_4'              => Str::upper($this->offer_4),
            'quantity'             => $this->quantity,
            'sale'                 => $this->sale,
            'price'                => $this->price,
            'rate'                 => $this->getRate(),
            'event_id'             => $this->event_id,
            'online'               => $this->online,
            'position'             => $this->position,
            'category'             => $this->getAssociateCategory(),
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

    private function getAssociateCategory()
    {
        $category = $this->categories()->first();

        if (null !== $category) {
            return $category->name;
        }

        return 'Gold';
    }

    private function getRate()
    {
        $q = $this->quantity + $this->sale;
        $d = 100 * $this->sale;

        if ($q > 0) {
            return number_format($d / $q, 2);
        }

        return 0;
    }
}
