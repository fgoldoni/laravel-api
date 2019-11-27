<?php

namespace Modules\Roles\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Activities\Transformers\ActivityTransformer;

class RoleCollection extends JsonResource
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
            'activities'  => ActivityTransformer::collection($this->activities),
            'users'       => $this->users->pluck('id'),
            'permissions' => $this->permissions->pluck('id'),
        ];
    }
}
