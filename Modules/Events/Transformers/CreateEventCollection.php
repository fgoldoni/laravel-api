<?php

namespace Modules\Events\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Categories\Repositories\Contracts\CategoriesRepository;
use Modules\Events\Entities\Event;

class CreateEventCollection extends JsonResource
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
            'start'            => $this->start->format('Y-m-d H:i:s'),
            'end'              => $this->end->format('Y-m-d H:i:s'),
            'url'              => $this->url,
            'color'            => $this->color,
            'all_day'          => $this->all_day,
            'online'           => $this->online,
            'attachments'      => $this->getAttachments(),
            'categories'       => [6],
            'categoriesList'       => $this->getCategories()
        ];
    }

    private function getCategories()
    {
        return app()->make(CategoriesRepository::class)->siblings('events', Event::class);
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
