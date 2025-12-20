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
        Schema::create('workshops', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('cover')->nullable();
            $table->enum('video_type', ['youtube', 'vimeo', 'none'])->default('none');
            $table->string('video_url')->nullable();
            $table->boolean('is_public')->default(false)->comment('true=public, false=students only');
            $table->boolean('featured')->default(false);
            $table->enum('status', ['draft', 'scheduled', 'published', 'archived'])->default('draft');
            $table->dateTime('scheduled_at')->nullable();
            $table->integer('duration')->nullable()->comment('Duration in minutes');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index(['status', 'is_public']);
            $table->index('featured');
            $table->index('scheduled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workshops');
    }
};
