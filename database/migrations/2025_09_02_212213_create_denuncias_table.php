<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('denuncias', function (Blueprint $table) {
            $table->id('IdDenuncia');
            $table->unsignedBigInteger('IdUsuario');
            $table->enum('TipoFoco', [1, 2, 3, 4])->comment('1=Água parada, 2=Lixo, 3=Entulho, 4=Outros');
            $table->text('Descricao')->nullable();
            $table->decimal('Longitude', 11, 8);
            $table->decimal('Latitude', 10, 8);
            $table->enum('Status', [1, 2, 3])->default(1)->comment('1=Pendente, 2=Em análise, 3=Resolvido');
            $table->dateTime('DataCriacao')->useCurrent();

            $table->foreign('IdUsuario')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('denuncias');
    }
};