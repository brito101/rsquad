<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        DB::table('permissions')->insert([
            /** ACL  1 to 11 */
            [
                'name' => 'Acessar ACL',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Listar Permissões',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Criar Permissões',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Editar Permissões',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Excluir Permissões',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Listar Perfis',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Criar Perfis',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Editar Perfis',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Excluir Perfis',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Sincronizar Perfis',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Atribuir Perfis',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],

            /** Users 12 to 17 */
            [
                'name' => 'Acessar Usuários',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Listar Usuários',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Criar Usuários',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Editar Usuário',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Editar Usuários',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Excluir Usuários',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            /** Administration Dashboard 18 */
            [
                'name' => 'Acessar Administração',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            /** Academy Dashboard 19 */
            [
                'name' => 'Acessar Academia',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            /** Course Categories 20*/
            [
                'name' => 'Acessar Configurações',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            /** Course Categories from 21 to 25*/
            [
                'name' => 'Acessar Categorias de Cursos',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Listar Categorias de Cursos',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Criar Categorias de Cursos',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Editar Categorias de Cursos',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Excluir Categorias de Cursos',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            /** Courses from 26 to 30*/
            [
                'name' => 'Acessar Cursos',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Listar Cursos',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Criar Cursos',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Editar Cursos',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Excluir Cursos',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            /** Classroom from 31 to 35*/
            [
                'name' => 'Acessar Aulas',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Listar Aulas',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Criar Aulas',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Editar Aulas',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Excluir Aulas',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
        ]);
    }
}
