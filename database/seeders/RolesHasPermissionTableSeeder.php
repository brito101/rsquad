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
        DB::table('role_has_permissions')->insert([
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
            /** Administration Dashboard 18 (Programmers, Administrators and Instructors) */
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
            /** Academy Dashboard 19 (Students) */
            [
                'permission_id' => 19,
                'role_id' => 4,
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
            /** Courses from 26 to 30 (Programmers, Administrators and Isntructors) */
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
        ]);
    }
}
