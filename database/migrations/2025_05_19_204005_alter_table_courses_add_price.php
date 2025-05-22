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
            $table->decimal('price', 8, 2)->default(0.00)->nullable()->after('active')->comment('Price of the course');
            $table->decimal('promotional_price', 8, 2)->default(0.00)->nullable()->after('price')->comment('Promotional price of the course');
            $table->boolean('is_promotional')->default(false)->after('promotional_price')->comment('Indicates if the course is on promotional price');
            $table->string('uri')->after('is_promotional')->comment('URI for the course, used for SEO and redirection');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('price');
            $table->dropColumn('promotional_price');
            $table->dropColumn('is_promotional');
            $table->dropColumn('uri');
        });
    }
};
