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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('cover')->nullable();
            $table->longText('description')->nullable();
            $table->string('status')->default('Rascunho');
            $table->boolean('active')->default(true);
            $table->string('sales_link')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });

        DB::statement('
        CREATE OR REPLACE VIEW `courses_view` AS
        SELECT c.id, c.name, c.cover, c.description, c.user_id, c.status, c.active, c.sales_link, u.name as editor
        FROM courses as c
        LEFT JOIN users as u ON c.user_id = u.id
        WHERE c.deleted_at IS NULL
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW courses_view');
        Schema::dropIfExists('courses');
    }
};
