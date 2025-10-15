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
        Schema::create('denuncias', function (Blueprint $table) {
            $table->id('IdDenuncia');
            $table->unsignedBigInteger('IdUsuario');
            $table->enum('TipoFoco', [1, 2, 3, 4])->comment('1=Água parada, 2=Lixo, 3=Entulho, 4=Outros');
            $table->text('Descricao')->nullable();
            $table->decimal('Longitude', 11, 8);
            $table->decimal('Latitude', 10, 8);

            // CORREÇÃO: Adicionado o valor '4' à lista de status permitidos.
            // O comentário também foi atualizado para refletir o novo significado dos números.
            $table->enum('Status', [1, 2, 3, 4])
                  ->default(1)
                  ->comment('1=Pendente, 2=Aprovada, 3=Resolvido, 4=Rejeitada');
            
            $table->dateTime('DataCriacao')->useCurrent();

            $table->foreign('IdUsuario')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('denuncias');
    }
};