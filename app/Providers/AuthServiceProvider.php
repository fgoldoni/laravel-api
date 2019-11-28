<?php

namespace App\Providers;

use App\Flag;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            return $user->hasRole(Flag::ROLE_ADMIN) ? true : null;
        });

        if (Schema::hasTable('permissions')) {
            foreach ($this->getPermissions() as $permission) {
                Gate::define(
                    $permission->name,
                    static function ($user) use ($permission) {
                        return $user->hasPermissionTo($permission);
                    }
                );
            }
        }
    }

    private function getPermissions()
    {
        return Permission::get();
    }
}
