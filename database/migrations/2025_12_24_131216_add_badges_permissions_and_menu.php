<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions if they don't exist
        $permissions = [
            'Acessar Badges',
            'Listar Badges',
            'Criar Badges',
            'Editar Badges',
            'Excluir Badges',
        ];

        $createdPermissions = [];
        foreach ($permissions as $permissionName) {
            $permission = Permission::firstOrCreate(
                ['name' => $permissionName, 'guard_name' => 'web'],
                ['created_at' => new DateTime('now')]
            );
            $createdPermissions[] = $permission;
        }

        // Get roles that should have these permissions
        $rolesToAssign = ['Programador', 'Administrador', 'Instrutor'];

        foreach ($rolesToAssign as $roleName) {
            $role = Role::where('name', $roleName)->first();

            if ($role) {
                foreach ($createdPermissions as $permission) {
                    // Check if permission is already assigned to avoid duplicates
                    if (! $role->hasPermissionTo($permission)) {
                        $role->givePermissionTo($permission);
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Remove permissions from roles
        $permissions = [
            'Acessar Badges',
            'Listar Badges',
            'Criar Badges',
            'Editar Badges',
            'Excluir Badges',
        ];

        $rolesToRemove = ['Programador', 'Administrador', 'Instrutor'];

        foreach ($rolesToRemove as $roleName) {
            $role = Role::where('name', $roleName)->first();

            if ($role) {
                foreach ($permissions as $permissionName) {
                    $permission = Permission::where('name', $permissionName)->first();
                    if ($permission && $role->hasPermissionTo($permission)) {
                        $role->revokePermissionTo($permission);
                    }
                }
            }
        }

        // Delete permissions
        foreach ($permissions as $permissionName) {
            Permission::where('name', $permissionName)->delete();
        }
    }
};
