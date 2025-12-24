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
        // Remove permissões de badges
        DB::table('permissions')->whereIn('name', [
            'Acessar Badges',
            'Listar Badges',
            'Criar Badges',
            'Editar Badges',
            'Excluir Badges',
        ])->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recria as permissões caso necessário
        $permissions = [
            ['name' => 'Acessar Badges', 'guard_name' => 'web'],
            ['name' => 'Listar Badges', 'guard_name' => 'web'],
            ['name' => 'Criar Badges', 'guard_name' => 'web'],
            ['name' => 'Editar Badges', 'guard_name' => 'web'],
            ['name' => 'Excluir Badges', 'guard_name' => 'web'],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $permission['name'], 'guard_name' => $permission['guard_name']],
                array_merge($permission, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
};
