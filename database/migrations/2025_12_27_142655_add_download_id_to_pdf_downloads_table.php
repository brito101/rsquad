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
        Schema::table('pdf_downloads', function (Blueprint $table) {
            $table->uuid('download_id')->after('user_id')->nullable()->comment('UUID único da marca d\'água');
        });

        // Preencher registros existentes com UUIDs
        DB::table('pdf_downloads')->whereNull('download_id')->get()->each(function ($record) {
            DB::table('pdf_downloads')
                ->where('id', $record->id)
                ->update(['download_id' => \Illuminate\Support\Str::uuid()->toString()]);
        });

        // Tornar a coluna obrigatória e única após preencher os dados
        Schema::table('pdf_downloads', function (Blueprint $table) {
            $table->uuid('download_id')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pdf_downloads', function (Blueprint $table) {
            $table->dropColumn('download_id');
        });
    }
};
