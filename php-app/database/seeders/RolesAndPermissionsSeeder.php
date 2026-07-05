<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        Permission::create(['name' => 'view_users', 'guard_name' => 'web', 'description' => 'View users list']);
        Permission::create(['name' => 'create_users', 'guard_name' => 'web', 'description' => 'Create new users']);
        Permission::create(['name' => 'edit_users', 'guard_name' => 'web', 'description' => 'Edit user details']);
        Permission::create(['name' => 'delete_users', 'guard_name' => 'web', 'description' => 'Delete users']);

        Permission::create(['name' => 'view_roles', 'guard_name' => 'web', 'description' => 'View roles list']);
        Permission::create(['name' => 'create_roles', 'guard_name' => 'web', 'description' => 'Create new roles']);
        Permission::create(['name' => 'edit_roles', 'guard_name' => 'web', 'description' => 'Edit role details']);
        Permission::create(['name' => 'delete_roles', 'guard_name' => 'web', 'description' => 'Delete roles']);

        Permission::create(['name' => 'view_permissions', 'guard_name' => 'web', 'description' => 'View permissions list']);
        Permission::create(['name' => 'create_permissions', 'guard_name' => 'web', 'description' => 'Create new permissions']);
        Permission::create(['name' => 'edit_permissions', 'guard_name' => 'web', 'description' => 'Edit permission details']);
        Permission::create(['name' => 'delete_permissions', 'guard_name' => 'web', 'description' => 'Delete permissions']);

        Permission::create(['name' => 'access_admin', 'guard_name' => 'web', 'description' => 'Access admin panel']);

        // Create roles and assign permissions
        $admin = Role::create(['name' => 'admin', 'guard_name' => 'web', 'description' => 'Administrator with full access']);
        $admin->givePermissionTo(Permission::all());

        $editor = Role::create(['name' => 'editor', 'guard_name' => 'web', 'description' => 'Editor with limited access']);
        $editor->givePermissionTo([
            'view_users',
            'edit_users',
            'view_roles',
            'view_permissions',
            'access_admin',
        ]);

        $user = Role::create(['name' => 'user', 'guard_name' => 'web', 'description' => 'Regular user']);
        $user->givePermissionTo([
            'access_admin',
        ]);

        Role::create(['name' => 'customer', 'guard_name' => 'web', 'description' => 'Customer registered via API']);
    }
}
