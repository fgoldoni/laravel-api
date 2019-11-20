<?php

namespace Modules\Users\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'first_name'      => $this->first_name,
            'last_name'       => $this->last_name,
            'full_name'       => $this->full_name,
            'email'           => $this->email,
            'role'            => $this->mapRole(),
            'all_permissions' => $this->all_permissions,
            'accessToken'     => $this->api_token,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
        ];
    }

    private function mapRole()
    {
        $role = $this->roles()->first();

        if (null === $role) {
            return 'user';
        }

        return mb_strtolower($role->name);
    }
}
