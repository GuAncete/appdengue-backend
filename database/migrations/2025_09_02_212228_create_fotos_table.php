<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fotos', function (Blueprint $table) {
            $table->id('IdFoto');
            $table->unsignedBigInteger('IdDenuncia');
            $table->string('CaminhoArquivo', 255);
            $table->foreign('IdDenuncia')->references('IdDenuncia')->on('denuncias')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fotos');
    }
};