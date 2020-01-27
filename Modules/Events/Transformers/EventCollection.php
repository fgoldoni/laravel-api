<?php

namespace Modules\Events\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Tickets\Transformers\TicketsCollection;

class EventCollection extends JsonResource
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
            'contact_phone'    => $this->contact_phone,
            'contact_email'    => $this->contact_email,
            'contact_name'     => $this->contact_name,
            'start'            => $this->start,
            'end'              => $this->end,
            'url'              => $this->url,
            'color'            => $this->color,
            'all_day'          => $this->all_day,
            'online'           => $this->online,
            'quantity'         => $this->getTotalTickets(),
            'price'            => $this->getPrice(),
            'attachments'      => $this->getAttachments(),
            'tickets'          => TicketsCollection::collection($this->tickets()->orderBy('tickets.position', 'asc')->get()),
            'categories'       => $this->getCategories(),
            'tags'             => $this->getTags(),
            'rating'           => random_int(2, 5),
            'user_id'          => $this->user->id,
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
        return $this->attachments()->orderBy('position', 'asc')->latest()->get()->map(function ($category) {
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

    private function getPrice()
    {
        $ticket = $this->tickets()->orderBy('tickets.price', 'asc')->first();

        if (null !== $ticket) {
            return $ticket->price;
        }

        return 0;
    }

    private function getTotalTickets()
    {
        $tickets = $this->tickets()->get();
        $sum = 0;

        foreach ($tickets as $ticket) {
            $sum += $ticket->quantity;
        }

        return $sum;
    }
}