<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 // ...
// ...
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        // Adiciona a coluna 'user_tipo' após a coluna 'name'
        $table->integer('user_tipo')->default(3)->after('name');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        // Remove a coluna caso a migration seja desfeita
        $table->dropColumn('user_tipo');
    });
}
// ...
};

