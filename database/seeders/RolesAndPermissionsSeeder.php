<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'manage-all',
            'manage-settings',
            'manage-categories',
            'manage-products',
            'manage-vendors',
            'manage-orders',
            'manage-users',
            'view-admin-dashboard',
            'view-vendor-dashboard',
            'manage-shop-settings',
            'customer-access',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Super Admin
        $roleSuperAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $roleSuperAdmin->givePermissionTo(Permission::all());

        // Admin
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleAdmin->givePermissionTo([
            'manage-categories',
            'manage-products',
            'manage-vendors',
            'manage-orders',
            'manage-users',
            'view-admin-dashboard',
        ]);

        // Staff
        $roleStaff = Role::firstOrCreate(['name' => 'staff']);
        $roleStaff->givePermissionTo([
            'manage-categories',
            'manage-products',
            'manage-orders',
            'view-admin-dashboard',
        ]);

        // Vendor
        $roleVendor = Role::firstOrCreate(['name' => 'vendor']);
        $roleVendor->givePermissionTo([
            'view-vendor-dashboard',
            'manage-products',
            'manage-shop-settings',
        ]);

        // Customer
        $roleCustomer = Role::firstOrCreate(['name' => 'customer']);
        $roleCustomer->givePermissionTo(['customer-access']);
    }
}
