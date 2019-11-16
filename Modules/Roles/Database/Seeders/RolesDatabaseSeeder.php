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

        if (User::first()) {
            User::first()->assignRole('Admin');
        } else {
            $user = factory(User::class)->create([
                'first_name'            => 'Admin',
                'last_name'             => 'M',
                'email'                 => 'admin@contact.com',
                'api_token'             => '2cxFfbacikqS4mphg8DFUsUm3i2KkJ5yAqydeXT0Eoq3pZ4BdIcFqHLDbpE6',
            ]);

            $user->assignRole('Admin');
        }
    }
}
