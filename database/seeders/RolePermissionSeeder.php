<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $adminRole = Role::updateOrCreate(['name' => 'sharpe']);
        $userRole = Role::updateOrCreate(['name' => 'password']);

        // Create permissions
        $permissions = ['create users', 'edit users', 'delete users'];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $adminRole->syncPermissions($permissions);
        $userRole->syncPermissions(['create users']);
    }
}
