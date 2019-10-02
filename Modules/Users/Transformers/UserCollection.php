<?php

namespace Modules\Users\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Activities\Transformers\ActivityTransformer;

class UserCollection extends JsonResource
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
            'first_name'  => $this->first_name,
            'last_name'   => $this->last_name,
            'full_name'   => $this->full_name,
            'email'       => $this->email,
            'roles'       => $this->mapRoles(),
            'permissions' => $this->mapPermissions(),
            'activities'  => ActivityTransformer::collection($this->activities),
            'actions'     => $this->actions,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }

    private function mapRoles()
    {
        return $this->roles->map(static function ($item) {
            return [
                'id'   => $item->id,
                'name' => $item->name,
            ];
        });
    }

    private function mapPermissions()
    {
        return $this->permissions->map(static function ($item) {
            return [
                'id'   => $item->id,
                'name' => $item->name,
            ];
        });
    }
}
