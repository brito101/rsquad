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
        Schema::create('pdf_downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('downloadable_type');
            $table->unsignedBigInteger('downloadable_id');
            $table->string('file_name');
            $table->timestamp('downloaded_at');
            $table->string('ip_address', 45);
            $table->timestamps();

            $table->index(['downloadable_type', 'downloadable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pdf_downloads');
    }
};
