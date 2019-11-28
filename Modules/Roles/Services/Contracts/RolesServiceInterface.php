<?php

namespace Modules\Roles\Services\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Modules\Roles\Entities\Role;

interface RolesServiceInterface
{
    public function storeRole(Request $request): Role;

    public function updateRole(Request $request, $id): Role;

    public function getRole(int $id): Role;

    public function getRoles(): Collection;

    public function transform(Role $role);
}
