<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesHasPermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        $rolePermissions = [
            /** ACL from 1 to  11 */
            [
                'permission_id' => 1,
                'role_id' => 1,
            ],
            [
                'permission_id' => 2,
                'role_id' => 1,
            ],
            [
                'permission_id' => 3,
                'role_id' => 1,
            ],
            [
                'permission_id' => 4,
                'role_id' => 1,
            ],
            [
                'permission_id' => 5,
                'role_id' => 1,
            ],
            [
                'permission_id' => 6,
                'role_id' => 1,
            ],
            [
                'permission_id' => 7,
                'role_id' => 1,
            ],
            [
                'permission_id' => 8,
                'role_id' => 1,
            ],
            [
                'permission_id' => 9,
                'role_id' => 1,
            ],
            [
                'permission_id' => 10,
                'role_id' => 1,
            ],
            [
                'permission_id' => 11,
                'role_id' => 1,
            ],
            /* A Profile assignment by administrator type user */
            [
                'permission_id' => 11,
                'role_id' => 2,
            ],
            /** Users from 12 to 17 (programmer and administrator) */
            [
                'permission_id' => 12,
                'role_id' => 1,
            ],
            [
                'permission_id' => 12,
                'role_id' => 2,
            ],
            [
                'permission_id' => 13,
                'role_id' => 1,
            ],
            [
                'permission_id' => 13,
                'role_id' => 2,
            ],
            [
                'permission_id' => 14,
                'role_id' => 1,
            ],
            [
                'permission_id' => 14,
                'role_id' => 2,
            ],
            [
                'permission_id' => 15,
                'role_id' => 1,
            ],
            [
                'permission_id' => 15,
                'role_id' => 2,
            ],
            [
                'permission_id' => 15,
                'role_id' => 3,
            ],
            [
                'permission_id' => 15,
                'role_id' => 4,
            ],
            [
                'permission_id' => 15,
                'role_id' => 5,
            ],
            [
                'permission_id' => 16,
                'role_id' => 1,
            ],
            [
                'permission_id' => 16,
                'role_id' => 2,
            ],
            [
                'permission_id' => 17,
                'role_id' => 1,
            ],
            [
                'permission_id' => 17,
                'role_id' => 2,
            ],
            /** Administration Dashboard 18 (Programmers, Administrators, Instructors and Monitors) */
            [
                'permission_id' => 18,
                'role_id' => 1,
            ],
            [
                'permission_id' => 18,
                'role_id' => 2,
            ],
            [
                'permission_id' => 18,
                'role_id' => 3,
            ],
            [
                'permission_id' => 18,
                'role_id' => 4,
            ],
            /** Academy Dashboard 19 (Students) */
            [
                'permission_id' => 19,
                'role_id' => 5,
            ],
            /** Course Configurations 20 (Programmers and Administrators) */
            [
                'permission_id' => 20,
                'role_id' => 1,
            ],
            [
                'permission_id' => 20,
                'role_id' => 2,
            ],
            /** Course Categories from 21 to 25 (Programmers and Administrators) */
            [
                'permission_id' => 21,
                'role_id' => 1,
            ],
            [
                'permission_id' => 21,
                'role_id' => 2,
            ],
            [
                'permission_id' => 22,
                'role_id' => 1,
            ],
            [
                'permission_id' => 22,
                'role_id' => 2,
            ],
            [
                'permission_id' => 23,
                'role_id' => 1,
            ],
            [
                'permission_id' => 23,
                'role_id' => 2,
            ],
            [
                'permission_id' => 24,
                'role_id' => 1,
            ],
            [
                'permission_id' => 24,
                'role_id' => 2,
            ],
            [
                'permission_id' => 25,
                'role_id' => 1,
            ],
            [
                'permission_id' => 25,
                'role_id' => 2,
            ],
            /** Courses from 26 to 30 (Programmers, Administrators, Instructors and Monitors) */
            [
                'permission_id' => 26,
                'role_id' => 1,
            ],
            [
                'permission_id' => 26,
                'role_id' => 2,
            ],
            [
                'permission_id' => 26,
                'role_id' => 3,
            ],
            [
                'permission_id' => 26,
                'role_id' => 4,
            ],
            [
                'permission_id' => 27,
                'role_id' => 1,
            ],
            [
                'permission_id' => 27,
                'role_id' => 2,
            ],
            [
                'permission_id' => 27,
                'role_id' => 3,
            ],
            [
                'permission_id' => 27,
                'role_id' => 4,
            ],
            [
                'permission_id' => 28,
                'role_id' => 1,
            ],
            [
                'permission_id' => 28,
                'role_id' => 2,
            ],
            [
                'permission_id' => 28,
                'role_id' => 3,
            ],
            [
                'permission_id' => 29,
                'role_id' => 1,
            ],
            [
                'permission_id' => 29,
                'role_id' => 2,
            ],
            [
                'permission_id' => 29,
                'role_id' => 3,
            ],
            [
                'permission_id' => 30,
                'role_id' => 1,
            ],
            [
                'permission_id' => 30,
                'role_id' => 2,
            ],
            [
                'permission_id' => 30,
                'role_id' => 3,
            ],
            /** Classroom from 31 to 35 (Programmers, Administrators, Instructors and Monitors) */
            [
                'permission_id' => 31,
                'role_id' => 1,
            ],
            [
                'permission_id' => 31,
                'role_id' => 2,
            ],
            [
                'permission_id' => 31,
                'role_id' => 3,
            ],
            [
                'permission_id' => 31,
                'role_id' => 4,
            ],
            [
                'permission_id' => 32,
                'role_id' => 1,
            ],
            [
                'permission_id' => 32,
                'role_id' => 2,
            ],
            [
                'permission_id' => 32,
                'role_id' => 3,
            ],
            [
                'permission_id' => 32,
                'role_id' => 4,
            ],
            [
                'permission_id' => 33,
                'role_id' => 1,
            ],
            [
                'permission_id' => 33,
                'role_id' => 2,
            ],
            [
                'permission_id' => 33,
                'role_id' => 3,
            ],
            [
                'permission_id' => 34,
                'role_id' => 1,
            ],
            [
                'permission_id' => 34,
                'role_id' => 2,
            ],
            [
                'permission_id' => 34,
                'role_id' => 3,
            ],
            [
                'permission_id' => 35,
                'role_id' => 1,
            ],
            [
                'permission_id' => 35,
                'role_id' => 2,
            ],
            [
                'permission_id' => 35,
                'role_id' => 3,
            ],
            /** Students from 36 to 40 (programmers, Administrators, Instructors and Monitors (list and show))*/
            [
                'permission_id' => 36,
                'role_id' => 1,
            ],
            [
                'permission_id' => 36,
                'role_id' => 2,
            ],
            [
                'permission_id' => 36,
                'role_id' => 3,
            ],
            [
                'permission_id' => 36,
                'role_id' => 4,
            ],
            [
                'permission_id' => 37,
                'role_id' => 1,
            ],
            [
                'permission_id' => 37,
                'role_id' => 2,
            ],
            [
                'permission_id' => 37,
                'role_id' => 3,
            ],
            [
                'permission_id' => 37,
                'role_id' => 4,
            ],
            [
                'permission_id' => 38,
                'role_id' => 1,
            ],
            [
                'permission_id' => 38,
                'role_id' => 2,
            ],
            [
                'permission_id' => 39,
                'role_id' => 1,
            ],
            [
                'permission_id' => 39,
                'role_id' => 2,
            ],
            [
                'permission_id' => 40,
                'role_id' => 1,
            ],
            [
                'permission_id' => 40,
                'role_id' => 2,
            ],
            /** Blog 41 (Programmer, Administrator, Instructors and Monitor) */
            [
                'permission_id' => 41,
                'role_id' => 1,
            ],
            [
                'permission_id' => 41,
                'role_id' => 2,
            ],
            [
                'permission_id' => 41,
                'role_id' => 3,
            ],
            [
                'permission_id' => 41,
                'role_id' => 4,
            ],
            /** Blog Categories from 42 to 45 (Programmer and Administrator)*/
            [
                'permission_id' => 42,
                'role_id' => 1,
            ],
            [
                'permission_id' => 42,
                'role_id' => 2,
            ],
            [
                'permission_id' => 43,
                'role_id' => 1,
            ],
            [
                'permission_id' => 43,
                'role_id' => 2,
            ],
            [
                'permission_id' => 44,
                'role_id' => 1,
            ],
            [
                'permission_id' => 44,
                'role_id' => 2,
            ],
            [
                'permission_id' => 45,
                'role_id' => 1,
            ],
            [
                'permission_id' => 45,
                'role_id' => 2,
            ],
            /** Blog Posts from 46 to 49 (Programmer, Administrator, Instructors and Monitors)*/
            [
                'permission_id' => 46,
                'role_id' => 1,
            ],
            [
                'permission_id' => 46,
                'role_id' => 2,
            ],
            [
                'permission_id' => 46,
                'role_id' => 3,
            ],
            [
                'permission_id' => 46,
                'role_id' => 4,
            ],
            [
                'permission_id' => 47,
                'role_id' => 1,
            ],
            [
                'permission_id' => 47,
                'role_id' => 2,
            ],
            [
                'permission_id' => 47,
                'role_id' => 3,
            ],
            [
                'permission_id' => 47,
                'role_id' => 4,
            ],
            [
                'permission_id' => 48,
                'role_id' => 1,
            ],
            [
                'permission_id' => 48,
                'role_id' => 2,
            ],
            [
                'permission_id' => 48,
                'role_id' => 3,
            ],
            [
                'permission_id' => 48,
                'role_id' => 4,
            ],
            [
                'permission_id' => 49,
                'role_id' => 1,
            ],
            [
                'permission_id' => 49,
                'role_id' => 2,
            ],
            [
                'permission_id' => 49,
                'role_id' => 3,
            ],
            [
                'permission_id' => 49,
                'role_id' => 4,
            ],
            /** Cheat Sheet 50 (Programmer, Administrator, Instructors and Monitors) */
            [
                'permission_id' => 50,
                'role_id' => 1,
            ],
            [
                'permission_id' => 50,
                'role_id' => 2,
            ],
            [
                'permission_id' => 50,
                'role_id' => 3,
            ],
            [
                'permission_id' => 50,
                'role_id' => 4,
            ],
            /** Cheat Sheet Posts from 51 to 54 (Programmer and Administrator)*/
            [
                'permission_id' => 51,
                'role_id' => 1,
            ],
            [
                'permission_id' => 51,
                'role_id' => 2,
            ],
            [
                'permission_id' => 52,
                'role_id' => 1,
            ],
            [
                'permission_id' => 52,
                'role_id' => 2,
            ],
            [
                'permission_id' => 53,
                'role_id' => 1,
            ],
            [
                'permission_id' => 53,
                'role_id' => 2,
            ],
            [
                'permission_id' => 54,
                'role_id' => 1,
            ],
            [
                'permission_id' => 54,
                'role_id' => 2,
            ],
            /** Cheat Sheet Posts from 55 to 58 (Programmer, Administrator, Instructors and Monitors)*/
            [
                'permission_id' => 55,
                'role_id' => 1,
            ],
            [
                'permission_id' => 55,
                'role_id' => 2,
            ],
            [
                'permission_id' => 55,
                'role_id' => 3,
            ],
            [
                'permission_id' => 55,
                'role_id' => 4,
            ],
            [
                'permission_id' => 56,
                'role_id' => 1,
            ],
            [
                'permission_id' => 56,
                'role_id' => 2,
            ],
            [
                'permission_id' => 56,
                'role_id' => 3,
            ],
            [
                'permission_id' => 56,
                'role_id' => 4,
            ],
            [
                'permission_id' => 57,
                'role_id' => 1,
            ],
            [
                'permission_id' => 57,
                'role_id' => 2,
            ],
            [
                'permission_id' => 57,
                'role_id' => 3,
            ],
            [
                'permission_id' => 57,
                'role_id' => 4,
            ],
            [
                'permission_id' => 58,
                'role_id' => 1,
            ],
            [
                'permission_id' => 58,
                'role_id' => 2,
            ],
            [
                'permission_id' => 58,
                'role_id' => 3,
            ],
            [
                'permission_id' => 58,
                'role_id' => 4,
            ],
            /** Course Modules from 59 to 63 (Programmers, Administrators, Instructors and Monitors) */
            [
                'permission_id' => 59,
                'role_id' => 1,
            ],
            [
                'permission_id' => 59,
                'role_id' => 2,
            ],
            [
                'permission_id' => 59,
                'role_id' => 3,
            ],
            [
                'permission_id' => 59,
                'role_id' => 4,
            ],
            [
                'permission_id' => 60,
                'role_id' => 1,
            ],
            [
                'permission_id' => 60,
                'role_id' => 2,
            ],
            [
                'permission_id' => 60,
                'role_id' => 3,
            ],
            [
                'permission_id' => 60,
                'role_id' => 4,
            ],
            [
                'permission_id' => 61,
                'role_id' => 1,
            ],
            [
                'permission_id' => 61,
                'role_id' => 2,
            ],
            [
                'permission_id' => 61,
                'role_id' => 3,
            ],
            [
                'permission_id' => 62,
                'role_id' => 1,
            ],
            [
                'permission_id' => 62,
                'role_id' => 2,
            ],
            [
                'permission_id' => 62,
                'role_id' => 3,
            ],
            [
                'permission_id' => 63,
                'role_id' => 1,
            ],
            [
                'permission_id' => 63,
                'role_id' => 2,
            ],
            [
                'permission_id' => 63,
                'role_id' => 3,
            ],
            /** Contacts 64 to 65 (Programmers, Administrators) */
            [
                'permission_id' => 64,
                'role_id' => 1,
            ],
            [
                'permission_id' => 64,
                'role_id' => 2,
            ],
            [
                'permission_id' => 65,
                'role_id' => 1,
            ],
            [
                'permission_id' => 65,
                'role_id' => 2,
            ],
            /** Testimonials 66 to 70 */
            // Programmers, Administrators, Instructors get all permissions (66-70)
            [
                'permission_id' => 66, // Acessar Depoimentos
                'role_id' => 1,
            ],
            [
                'permission_id' => 66,
                'role_id' => 2,
            ],
            [
                'permission_id' => 66,
                'role_id' => 3,
            ],
            [
                'permission_id' => 67, // Listar Depoimentos
                'role_id' => 1,
            ],
            [
                'permission_id' => 67,
                'role_id' => 2,
            ],
            [
                'permission_id' => 67,
                'role_id' => 3,
            ],
            [
                'permission_id' => 68, // Criar Depoimentos
                'role_id' => 1,
            ],
            [
                'permission_id' => 68,
                'role_id' => 2,
            ],
            [
                'permission_id' => 68,
                'role_id' => 3,
            ],
            [
                'permission_id' => 68, // Criar Depoimentos - Student
                'role_id' => 4,
            ],
            [
                'permission_id' => 69, // Editar Depoimentos
                'role_id' => 1,
            ],
            [
                'permission_id' => 69,
                'role_id' => 2,
            ],
            [
                'permission_id' => 69,
                'role_id' => 3,
            ],
            [
                'permission_id' => 70, // Excluir Depoimentos
                'role_id' => 1,
            ],
            [
                'permission_id' => 70,
                'role_id' => 2,
            ],
            [
                'permission_id' => 70,
                'role_id' => 3,
            ],
            /** Workshops 71 to 75 (Programador, Administrador, Instrutor) */
            [
                'permission_id' => 71, // Acessar Workshops
                'role_id' => 1,
            ],
            [
                'permission_id' => 71,
                'role_id' => 2,
            ],
            [
                'permission_id' => 71,
                'role_id' => 3,
            ],
            [
                'permission_id' => 72, // Listar Workshops
                'role_id' => 1,
            ],
            [
                'permission_id' => 72,
                'role_id' => 2,
            ],
            [
                'permission_id' => 72,
                'role_id' => 3,
            ],
            [
                'permission_id' => 73, // Criar Workshops
                'role_id' => 1,
            ],
            [
                'permission_id' => 73,
                'role_id' => 2,
            ],
            [
                'permission_id' => 73,
                'role_id' => 3,
            ],
            [
                'permission_id' => 74, // Editar Workshops
                'role_id' => 1,
            ],
            [
                'permission_id' => 74,
                'role_id' => 2,
            ],
            [
                'permission_id' => 74,
                'role_id' => 3,
            ],
            [
                'permission_id' => 75, // Excluir Workshops
                'role_id' => 1,
            ],
            [
                'permission_id' => 75,
                'role_id' => 2,
            ],
            [
                'permission_id' => 75,
                'role_id' => 3,
            ],
            /** Badges 76 to 80 (Programmer, Administrator and Instructor) */
            [
                'permission_id' => 76, // Acessar Badges
                'role_id' => 1,
            ],
            [
                'permission_id' => 76,
                'role_id' => 2,
            ],
            [
                'permission_id' => 76,
                'role_id' => 3,
            ],
            [
                'permission_id' => 77, // Listar Badges
                'role_id' => 1,
            ],
            [
                'permission_id' => 77,
                'role_id' => 2,
            ],
            [
                'permission_id' => 77,
                'role_id' => 3,
            ],
            [
                'permission_id' => 78, // Criar Badges
                'role_id' => 1,
            ],
            [
                'permission_id' => 78,
                'role_id' => 2,
            ],
            [
                'permission_id' => 78,
                'role_id' => 3,
            ],
            [
                'permission_id' => 79, // Editar Badges
                'role_id' => 1,
            ],
            [
                'permission_id' => 79,
                'role_id' => 2,
            ],
            [
                'permission_id' => 79,
                'role_id' => 3,
            ],
            [
                'permission_id' => 80, // Excluir Badges
                'role_id' => 1,
            ],
            [
                'permission_id' => 80,
                'role_id' => 2,
            ],
            [
                'permission_id' => 80,
                'role_id' => 3,
            ],
        ];

        // Insert only role-permission associations that don't exist yet
        foreach ($rolePermissions as $rolePermission) {
            DB::table('role_has_permissions')->updateOrInsert(
                ['permission_id' => $rolePermission['permission_id'], 'role_id' => $rolePermission['role_id']],
                $rolePermission
            );
        }
    }
}
