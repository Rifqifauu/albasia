<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Policies\RolePolicy;
use App\Policies\PermissionPolicy;
use Spatie\Permission\Models\Role;
use App\Models\Tallies;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function (User $user, string $ability) {
    return $user->isSuperAdmin() ? true: null;
});
Gate::policy(Role::class, RolePolicy::class);
Gate::policy(Permission::class, PermissionPolicy::class);

//
    }
}
