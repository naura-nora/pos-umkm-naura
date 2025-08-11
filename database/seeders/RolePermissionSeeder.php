<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run() {
    // Reset cache
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    // Buat permissions
    $permissions = [
        'manage_users',
        'manage_roles',
        'view_activity_logs'
    ];

    foreach ($permissions as $permission) {
        Permission::create(['name' => $permission]);
    }

    // Role Admin
    $admin = Role::create(['name' => 'admin']);
    $admin->givePermissionTo($permissions);

    // Role Kasir
    Role::create(['name' => 'kasir']);
}
}
