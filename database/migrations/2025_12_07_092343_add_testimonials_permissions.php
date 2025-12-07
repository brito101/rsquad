<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert permissions if they don't exist
        $permissions = [
            'Acessar Depoimentos',
            'Listar Depoimentos',
            'Criar Depoimentos',
            'Editar Depoimentos',
            'Excluir Depoimentos',
        ];

        foreach ($permissions as $permission) {
            $exists = DB::table('permissions')
                ->where('name', $permission)
                ->where('guard_name', 'web')
                ->exists();

            if (!$exists) {
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

        // Get role IDs
        $roles = DB::table('roles')
            ->whereIn('name', ['Programador', 'Administrador', 'Instrutor', 'Aluno'])
            ->pluck('id', 'name');

        // Assign permissions to roles
        $rolePermissions = [];

        // Programador and Administrador get all permissions
        foreach (['Programador', 'Administrador'] as $roleName) {
            if (isset($roles[$roleName])) {
                foreach ($permissionIds as $permissionId) {
                    $exists = DB::table('role_has_permissions')
                        ->where('permission_id', $permissionId)
                        ->where('role_id', $roles[$roleName])
                        ->exists();

                    if (!$exists) {
                        $rolePermissions[] = [
                            'permission_id' => $permissionId,
                            'role_id' => $roles[$roleName],
                        ];
                    }
                }
            }
        }

        // Instrutor gets all permissions
        if (isset($roles['Instrutor'])) {
            foreach ($permissionIds as $permissionId) {
                $exists = DB::table('role_has_permissions')
                    ->where('permission_id', $permissionId)
                    ->where('role_id', $roles['Instrutor'])
                    ->exists();

                if (!$exists) {
                    $rolePermissions[] = [
                        'permission_id' => $permissionId,
                        'role_id' => $roles['Instrutor'],
                    ];
                }
            }
        }

        // Aluno only gets 'Criar Depoimentos'
        if (isset($roles['Aluno']) && isset($permissionIds['Criar Depoimentos'])) {
            $exists = DB::table('role_has_permissions')
                ->where('permission_id', $permissionIds['Criar Depoimentos'])
                ->where('role_id', $roles['Aluno'])
                ->exists();

            if (!$exists) {
                $rolePermissions[] = [
                    'permission_id' => $permissionIds['Criar Depoimentos'],
                    'role_id' => $roles['Aluno'],
                ];
            }
        }

        // Insert role permissions
        if (!empty($rolePermissions)) {
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
            'Acessar Depoimentos',
            'Listar Depoimentos',
            'Criar Depoimentos',
            'Editar Depoimentos',
            'Excluir Depoimentos',
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
            ->where('guard_name', 'web')
            ->delete();

        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
