<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Programador',
                'email' => env('PROGRAMMER_EMAIL'),
                'password' => bcrypt(env('PROGRAMMER_PASSWD')),
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Administrator',
                'email' => env('ADMIN_EMAIL'),
                'password' => bcrypt(env('ADMIN_PASSWD')),
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Instrutor',
                'email' => env('INSTRUCTOR_EMAIL'),
                'password' => bcrypt(env('INSTRUCTOR_PASSWD')),
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Monitor',
                'email' => env('MONITOR_EMAIL'),
                'password' => bcrypt(env('MONITOR_PASSWD')),
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Aluno',
                'email' => env('STUDENT_EMAIL'),
                'password' => bcrypt(env('STUDENT_PASSWD')),
                'created_at' => new DateTime('now'),
            ],
        ]);
    }
}
