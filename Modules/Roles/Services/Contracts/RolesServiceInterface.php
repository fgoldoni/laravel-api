<?php

namespace Modules\Roles\Services\Contracts;

use Illuminate\Http\Request;
use Modules\Roles\Entities\Role;

interface RolesServiceInterface
{
    public function storeRole(Request $request): Role;

    public function updateRole(Request $request, $id): Role;
}
