<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cache
        app()[\Satie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'kasir']);
        Role::create(['name' => 'pelanggan']);

        // Buat permissions (opsional)
        $permissions = [
            'manage_users',
            'manage_products',
            'manage_categories',
            'view_transactions',
            'create_transactions',
            'update_transactions',
            'delete_transactions',
            'view_activity_logs'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        $admin = Role::findByName('admin');
        $admin->givePermissionTo($permissions);

        $kasir = Role::findByName('kasir');
        $kasir->givePermissionTo(['view_transactions', 'create_transactions', 'update_transactions']);
    }
}