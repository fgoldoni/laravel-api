<?php

namespace Modules\Users\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class UsersCollection extends JsonResource
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
            'id'                                => $this->id,
            'first_name'                        => $this->first_name,
            'last_name'                         => $this->last_name,
            'full_name'                         => $this->full_name,
            'name'                              => $this->full_name,
            'email'                             => $this->email,
            'dob'                               => $this->dob,
            'gender'                            => $this->gender,
            'country'                           => $this->country,
            'company'                           => $this->company,
            'department'                        => $this->department,
            'mobile'                            => $this->mobile,
            'website'                           => $this->website,
            'languages_known'                   => $this->languages_known,
            'contact_options'                   => $this->contact_options,
            'is_verified'                       => null !== $this->email_verified_at,
            'status'                            => $this->getStatus(),
            'roles'                             => $this->mapRoles(),
            'permissions'                       => $this->mapPermissions(),
            'actions'                           => $this->actions,
            'created_at'                        => $this->created_at,
            'updated_at'                        => $this->updated_at,
        ];
    }

    private function mapRoles()
    {
        return implode('|', $this->roles->map(static function ($item) {
            return $item->name;
        })->toArray());
    }

    private function mapPermissions()
    {
        $permissions = [
            'users' => [
                'read'         => $this->hasPermissionTo('READ_USERS'),
                'write'        => $this->hasPermissionTo('EDIT_USERS'),
                'update'       => $this->hasPermissionTo('UPDATE_USERS'),
                'delete'       => $this->hasPermissionTo('DELETE_USERS'),
                'restore'      => $this->hasPermissionTo('RESTORE_USERS'),
                'force_delete' => $this->hasPermissionTo('FORCE_DELETE_USERS'),
            ],
            'roles' => [
                'read'         => $this->hasPermissionTo('READ_ROLES'),
                'write'        => $this->hasPermissionTo('EDIT_ROLES'),
                'update'       => $this->hasPermissionTo('UPDATE_ROLES'),
                'delete'       => $this->hasPermissionTo('DELETE_ROLES'),
                'restore'      => $this->hasPermissionTo('RESTORE_ROLES'),
                'force_delete' => $this->hasPermissionTo('FORCE_DELETE_ROLES'),
            ]
        ];

        return $permissions;
    }

    private function getStatus()
    {
        if (null === $this->deleted_at) {
            return 'active';
        }

        return 'blocked';
    }
}
