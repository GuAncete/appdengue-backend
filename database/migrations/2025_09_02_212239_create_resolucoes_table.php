<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resolucoes', function (Blueprint $table) {
            $table->id('IdResolucao');
            $table->unsignedBigInteger('IdDenuncia');
            $table->unsignedBigInteger('IdUsuario');
            $table->dateTime('DataResolucao')->useCurrent();
            $table->text('Observacao')->nullable();

            $table->foreign('IdDenuncia')->references('IdDenuncia')->on('denuncias')->onDelete('cascade');
            $table->foreign('IdUsuario')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resolucoes');
    }
};