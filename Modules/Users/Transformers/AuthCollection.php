<?php

namespace Modules\Users\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                  => $this->id,
            'first_name'          => $this->first_name,
            'last_name'           => $this->last_name,
            'full_name'           => $this->full_name,
            'avatar'              => $this->getAvatar(),
            'name'                => $this->full_name,
            'email'               => $this->email,
            'city'                => $this->city,
            'mobile'              => $this->mobile,
            'address'             => $this->address,
            'role'                => $this->mapRole(),
            'all_permissions'     => $this->all_permissions,
            'unreadNotifications' => $this->getUnreadNotifications()
        ];
    }

    private function mapRole()
    {
        $role = $this->roles()->first();

        if (null === $role) {
            return 'user';
        }

        return str_slug($role->name);
    }

    private function getUnreadNotifications()
    {
        $unreadNotifications = [];
        $notifications = $this->unreadNotifications()->latest()->get();

        foreach ($notifications as $key => $item) {
            $unreadNotifications[] = [
                'index'         => $key,
                'title'         => $item->data['title'],
                'msg'           => $item->data['msg'],
                'icon'          => $item->data['icon'],
                'time'          => $item->data['time'],
                'category'      => $item->data['category']
            ];
        }

        return $unreadNotifications;
    }

    private function getAvatar()
    {
        if ($avatar = $this->attachments()->latest()->first()) {
            return $avatar->url;
        }

        return '';
    }
}
