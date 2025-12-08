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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->timestamp('started_at')->nullable()->comment('Data da primeira aula assistida');
            $table->timestamp('issued_at')->comment('Data de conclusão (100%)');
            $table->string('verification_code', 64)->unique()->comment('Código único de verificação');
            $table->string('pdf_path')->nullable()->comment('Caminho do PDF gerado');
            $table->timestamps();

            // Índices para performance
            $table->index(['user_id', 'course_id']);
            $table->index('verification_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
