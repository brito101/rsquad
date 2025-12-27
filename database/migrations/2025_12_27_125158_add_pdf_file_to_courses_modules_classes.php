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
        Schema::table('courses', function (Blueprint $table) {
            $table->string('pdf_file')->nullable()->after('badge_image');
        });

        Schema::table('course_modules', function (Blueprint $table) {
            $table->string('pdf_file')->nullable()->after('description');
        });

        Schema::table('classrooms', function (Blueprint $table) {
            $table->string('pdf_file')->nullable()->after('vimeo_player_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('pdf_file');
        });

        Schema::table('course_modules', function (Blueprint $table) {
            $table->dropColumn('pdf_file');
        });

        Schema::table('classrooms', function (Blueprint $table) {
            $table->dropColumn('pdf_file');
        });
    }
};
