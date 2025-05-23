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
        Schema::create('course_modules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('cover')->nullable();
            $table->longText('description')->nullable();
            $table->integer('order')->default(0);
            $table->string('status')->default('Rascunho');
            $table->boolean('active')->default(true);
            $table->string('link')->nullable();
            $table->date('release_date')->nullable();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });

        DB::statement('
        CREATE OR REPLACE VIEW `course_modules_view` AS
        SELECT c.id, c.name, c.order, c.status, c.active, c.link, c.release_date, c.course_id, co.name as course, c.user_id, u.name as editor
        FROM course_modules as c
        LEFT JOIN courses as co ON c.course_id = co.id
        LEFT JOIN users as u ON c.user_id = u.id
        WHERE c.deleted_at IS NULL
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW course_modules_view');
        Schema::dropIfExists('course_modules');
    }
};
