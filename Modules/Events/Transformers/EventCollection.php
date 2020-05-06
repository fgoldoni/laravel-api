<?php

namespace Modules\Events\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Categories\Repositories\Contracts\CategoriesRepository;
use Modules\Events\Entities\Event;
use Modules\Tickets\Entities\Ticket;
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
            'theme'            => $this->theme,
            'quantity'         => $this->getTotalTickets(),
            'price'            => $this->getPrice(),
            'logo'             => $this->getLogo(),
            'banner'           => $this->getBanner(),
            'background'       => $this->getBackground(),
            'flyer'            => $this->getFlyer(),
            'profile'          => $this->getProfile(),
            'attachments'      => $this->getAttachments(),
            'tickets'          => TicketsCollection::collection($this->tickets()->orderBy('tickets.position', 'asc')->get()),
            'categories'       => $this->getCategories(),
            'categoriesTags'   => $this->getCategoriesTags(),
            'categoriesList'   => $this->getCategoriesList(),
            'ticketsList'      => $this->ticketsList(),
            'tags'             => $this->getTags(),
            'rating'           => random_int(2, 5),
            'user_id'          => $this->user->id,
            'user'             => [
                'full_name' => $this->user->full_name,
                'email'     => $this->user->email
            ],
            'created_at' => $this->created_at,
            'deleted_at' => $this->deleted_at,
        ];
    }

    private function getCategories()
    {
        return $this->categories()->get()->pluck(['id']);
    }

    private function getCategoriesTags()
    {
        return $this->categories()->get()->pluck(['name']);
    }

    private function getCategoriesList()
    {
        return app()->make(CategoriesRepository::class)->siblings('events', Event::class);
    }

    private function getAttachments()
    {
        return $this->attachments()->orderBy('position', 'asc')->get()->map(function ($attachment) {
            return [
                'url' => asset($attachment->url)
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

    private function getBanner()
    {
        $attachment = $this->attachments()->where('position', 2)->first();

        if ($attachment) {
            return asset($attachment->url);
        } else {
            return asset('storage/uploads/cover/1587022819_i5i2.jpg');
        }
    }

    private function ticketsList()
    {
        return app()->make(CategoriesRepository::class)->siblings('tickets', Ticket::class);
    }

    private function getBackground()
    {
        $attachment = $this->attachments()->where('position', 1)->first();

        if ($attachment) {
            return asset($attachment->url);
        } else {
            return asset('storage/uploads/cover/1587022819_i5i2.jpg');
        }
    }

    private function getFlyer()
    {
        $attachment = $this->attachments()->where('position', 3)->first();

        if ($attachment) {
            return asset($attachment->url);
        } else {
            return asset('storage/uploads/cover/1587022819_i5i2.jpg');
        }
    }

    private function getLogo()
    {
        $attachment = $this->attachments()->where('position', 0)->first();

        if ($attachment) {
            return asset($attachment->url);
        } else {
            return asset('storage/uploads/events/1587691120_o6X2.png');
        }
    }

    private function getProfile()
    {
        $attachment = $this->attachments()->where('position', 4)->first();

        if ($attachment) {
            return asset($attachment->url);
        } else {
            return asset('storage/uploads/events/1587691120_o6X2.png');
        }
    }
}
