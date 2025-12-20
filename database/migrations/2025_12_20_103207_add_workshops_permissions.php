<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert permissions if they don't exist
        $permissions = [
            'Acessar Workshops',
            'Listar Workshops',
            'Criar Workshops',
            'Editar Workshops',
            'Excluir Workshops',
        ];

        foreach ($permissions as $permission) {
            $exists = DB::table('permissions')
                ->where('name', $permission)
                ->where('guard_name', 'web')
                ->exists();

            if (! $exists) {
                DB::table('permissions')->insert([
                    'name' => $permission,
                    'guard_name' => 'web',
                    'created_at' => new DateTime('now'),
                    'updated_at' => new DateTime('now'),
                ]);
            }
        }

        // Get permission IDs
        $permissionIds = DB::table('permissions')
            ->whereIn('name', $permissions)
            ->pluck('id', 'name');

        // Get role IDs (all roles except Monitor and Aluno)
        $roles = DB::table('roles')
            ->whereIn('name', ['Programador', 'Administrador', 'Instrutor'])
            ->pluck('id', 'name');

        // Assign permissions to roles
        $rolePermissions = [];

        // Programador, Administrador and Instrutor get all permissions
        foreach (['Programador', 'Administrador', 'Instrutor'] as $roleName) {
            if (isset($roles[$roleName])) {
                foreach ($permissionIds as $permissionId) {
                    $exists = DB::table('role_has_permissions')
                        ->where('permission_id', $permissionId)
                        ->where('role_id', $roles[$roleName])
                        ->exists();

                    if (! $exists) {
                        $rolePermissions[] = [
                            'permission_id' => $permissionId,
                            'role_id' => $roles[$roleName],
                        ];
                    }
                }
            }
        }

        // Insert role permissions
        if (! empty($rolePermissions)) {
            DB::table('role_has_permissions')->insert($rolePermissions);
        }

        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $permissions = [
            'Acessar Workshops',
            'Listar Workshops',
            'Criar Workshops',
            'Editar Workshops',
            'Excluir Workshops',
        ];

        // Get permission IDs
        $permissionIds = DB::table('permissions')
            ->whereIn('name', $permissions)
            ->pluck('id');

        // Delete role_has_permissions entries
        DB::table('role_has_permissions')
            ->whereIn('permission_id', $permissionIds)
            ->delete();

        // Delete permissions
        DB::table('permissions')
            ->whereIn('name', $permissions)
            ->delete();

        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
