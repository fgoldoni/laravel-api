<?php

namespace Modules\Roles\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class RolesCollection extends JsonResource
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
        return parent::toArray($request);
    }
}