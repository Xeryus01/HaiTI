<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (! Schema::hasTable('roles')) {
            return;
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $roles = [];

        foreach (['Admin', 'Teknisi', 'User'] as $roleName) {
            $roles[$roleName] = Role::findOrCreate($roleName, 'web');
        }

        if (! Schema::hasTable('permissions') || ! Schema::hasTable('role_has_permissions')) {
            return;
        }

        $permissions = [
            'view tickets',
            'create tickets',
            'update tickets',
            'assign tickets',
            'comment tickets',
            'view assets',
            'manage assets',
            'view reservations',
            'create reservations',
            'manage reservations',
            'view dashboard',
        ];

        foreach ($permissions as $permissionName) {
            Permission::findOrCreate($permissionName, 'web');
        }

        $roles['Admin']->syncPermissions($permissions);
        $roles['Teknisi']->syncPermissions([
            'view tickets',
            'update tickets',
            'assign tickets',
            'comment tickets',
            'view assets',
            'manage assets',
            'view dashboard',
        ]);
        $roles['User']->syncPermissions([
            'view tickets',
            'create tickets',
            'comment tickets',
            'view assets',
            'view reservations',
            'create reservations',
            'view dashboard',
        ]);
    }
}
