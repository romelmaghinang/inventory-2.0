<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class PermissionRoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guardName = 'api'; 

        $permissions = [
            'create-users', 'create-permission', 'create-role', 'assign-role', 'assign-permission',
            'pick-finish', 'pick-start', 'pack', 'ship', 'inventory',
            'create-qbclass', 'update-qbclass', 'view-qbclass', 'delete-qbclass',
            'create-taxrate', 'update-taxrate', 'view-taxrate', 'delete-taxrate',
            'create-payment-terms', 'update-payment-terms', 'view-payment-terms', 'delete-payment-terms',
            'create-currency', 'update-currency', 'view-currency', 'delete-currency',
            'create-vendor', 'update-vendor', 'view-vendor', 'delete-vendor',
            'create-part', 'update-part', 'view-part', 'delete-part',
            'create-purchase-order', 'update-purchase-order', 'view-purchase-order', 'delete-purchase-order',
            'create-product', 'update-product', 'view-product', 'delete-product',
            'create-sales-order', 'update-sales-order', 'view-sales-order', 'delete-sales-order',
            'create-pick', 'update-pick', 'view-pick', 'delete-pick',
            'create-location', 'update-location', 'view-location', 'delete-location',
            'create-state', 'view-country', 'view-state', 'update-state', 'delete-state',
            'create-customer', 'view-customer', 'update-customer', 'delete-customer',
            'pick-start', 'pick-finish', 'receipt-reconciled', 'receipt-fulfilled', 'pack', 'ship', 'inventory', 'receiving', 'receipt-void'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => $guardName,
            ]);
        }

        $adminRole = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => $guardName,
        ]);

        $adminRole->syncPermissions($permissions);

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'), 
            ]
        );

        $adminUser->assignRole($adminRole);

        foreach ($permissions as $permission) {
            $permissionInstance = Permission::where('name', $permission)->first();
            $adminUser->givePermissionTo($permissionInstance);
        }
    }
}
