<?php

namespace Modules\Activities\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityTransformer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->resource->id,
            'action'     => $this->resource->description,
            'causer'     => $this->getCauser(),
            'subject'    => $this->getSubject(),
            'properties' => $this->getProperties(),
            'created_at' => $this->created_at->format('d, M Y H:i'),
            'ago'        => $this->created_at->diffForHumans(),
        ];
    }

    private function getCauser()
    {
        return null === $this->resource->causer_id
            ? 'System'
            : $this->resource->causer->id . ':' . $this->resource->causer->full_name;
    }

    private function getSubject()
    {
        return $this->resource->subject_id . ':' . $this->resource->subject_type;
    }

    private function getProperties()
    {
        $items = [];
        $old = $this->resource->properties->get('old');
        $attributes = $this->resource->properties->get('attributes');

        if (isset($attributes)) {
            if (isset($old)) {
                foreach ($attributes as $key => $property) {
                    if (0 !== mb_strlen($property) || 0 !== mb_strlen($old[$key])) {
                        $items[$key] = "<s>{$old[$key]}</s>  {$property}";
                    }
                }
            } else {
                foreach ($attributes as $key => $property) {
                    if (\is_string($property)) {
                        $items[$key] = $property;
                    }
                }
            }
        } else {
            foreach ($this->resource->properties as $key => $property) {
                if (\is_string($property)) {
                    $items[$key] = $property;
                }
            }
        }

        return $items;
    }
}
