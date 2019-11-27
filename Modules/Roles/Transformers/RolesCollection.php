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
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'users'       => $this->getUsers(),
            'permissions' => $this->getPermissions()
        ];
    }

    private function getUsers()
    {
        return $this->users->map(function ($user) {
            return [
                'full_name' => $user->full_name,
            ];
        })->reject(function ($user) {
            return empty($user);
        });
    }

    private function getPermissions()
    {
        return $this->permissions->map(function ($user) {
            return [
                'name' => $user->name,
            ];
        })->reject(function ($user) {
            return empty($user);
        });
    }
}
