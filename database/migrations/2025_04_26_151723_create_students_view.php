<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement('
        CREATE OR REPLACE VIEW `students_view` AS
        SELECT u.id, u.name, u.email, mr.role_id, r.name as type, u.photo, u.bio, u.linkedin, u.instagram, u.youtube
        FROM users as u
        LEFT JOIN model_has_roles as mr ON mr.model_id = u.id
        LEFT JOIN roles as r ON r.id = mr.role_id
        WHERE u.deleted_at IS NULL AND r.name = "Aluno"
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW students_view');
    }
};
