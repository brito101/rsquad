<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Adiciona campos de badge diretamente na tabela courses
        Schema::table('courses', function (Blueprint $table) {
            $table->string('badge_name')->nullable()->after('cover');
            $table->string('badge_image')->nullable()->after('badge_name');
        });

        // Remove tabela pivot desnecessária
        Schema::dropIfExists('course_badge');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recria tabela pivot
        Schema::create('course_badge', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('badge_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        // Remove campos de badge da tabela courses
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['badge_name', 'badge_image']);
        });
    }
};
