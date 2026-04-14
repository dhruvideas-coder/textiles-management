<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'shops.manage',
            'users.manage',
            'subscriptions.manage',
            'platform.analytics.view',
            'impersonation.use',
            'bills.manage',
            'challans.manage',
            'customers.manage',
            'inventory.manage',
            'shop.analytics.view',
            'shop.settings.manage',
            'staff.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $superAdmin = Role::firstOrCreate(['name' => User::ROLE_SUPER_ADMIN, 'guard_name' => 'web']);
        $owner = Role::firstOrCreate(['name' => User::ROLE_OWNER, 'guard_name' => 'web']);
        $staff = Role::firstOrCreate(['name' => User::ROLE_STAFF, 'guard_name' => 'web']);

        $superAdmin->syncPermissions(Permission::all());
        $owner->syncPermissions([
            'bills.manage',
            'challans.manage',
            'customers.manage',
            'inventory.manage',
            'shop.analytics.view',
            'shop.settings.manage',
            'staff.manage',
        ]);
        $staff->syncPermissions([
            'bills.manage',
            'challans.manage',
            'customers.manage',
            'inventory.manage',
            'shop.analytics.view',
        ]);
    }
}
