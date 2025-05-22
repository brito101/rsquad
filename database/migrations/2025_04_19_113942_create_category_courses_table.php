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
        Schema::create('category_courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('cover')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });

        DB::statement('
        CREATE OR REPLACE VIEW `category_courses_view` AS
        SELECT cc.id, cc.name, cc.cover, cc.description, cc.user_id, u.name as editor
        FROM category_courses as cc
        LEFT JOIN users as u ON cc.user_id = u.id
        WHERE cc.deleted_at IS NULL
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW category_courses_view');
        Schema::dropIfExists('category_courses');
    }
};
