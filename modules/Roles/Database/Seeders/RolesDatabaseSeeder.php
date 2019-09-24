<?php

namespace Modules\Roles\Database\Seeders;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Model::unguard();

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (config('access.roles', []) as $role) {
            if (!Role::where('name', $role['name'])->where('guard_name', $role['guard_name'])->exists()) {
                Role::create($role);
            }
        }

        foreach (config('access.permissions', []) as $permission) {
            if (!Permission::where('name', $permission['name'])->where('guard_name', $permission['guard_name'])->exists()) {
                Permission::create($permission);
            }
        }

        Role::where('name', 'Admin')->first()->givePermissionTo(Permission::all());
        User::first()->assignRole('Admin');
    }
}
