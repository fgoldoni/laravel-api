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

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'READ_USERS', 'guard_name' => 'web']);
        Permission::create(['name' => 'EDIT_USERS', 'guard_name' => 'web']);
        Permission::create(['name' => 'UPDATE_USERS', 'guard_name' => 'web']);
        Permission::create(['name' => 'DELETE_USERS', 'guard_name' => 'web']);

        // create roles and assign created permissions

        // this can be done as separate statements
        Role::create(['name' => 'User', 'guard_name' => 'web']);

        // or may be done by chaining
        Role::create(['name' => 'Admin', 'guard_name' => 'web'])->givePermissionTo(Permission::all());

        User::first()->assignRole('Admin');
    }
}
