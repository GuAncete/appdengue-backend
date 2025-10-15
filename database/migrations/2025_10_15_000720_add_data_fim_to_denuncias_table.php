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
        Schema::table('denuncias', function (Blueprint $table) {
            // Adiciona a coluna 'data_fim' que pode ser nula
            $table->timestamp('data_fim')->nullable()->after('DataCriacao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('denuncias', function (Blueprint $table) {
            // Remove a coluna caso a migration seja desfeita
            $table->dropColumn('data_fim');
        });
    }
};