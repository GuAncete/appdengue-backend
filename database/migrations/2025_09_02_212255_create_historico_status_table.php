<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historico_status', function (Blueprint $table) {
            $table->id('IdHistorico');
            $table->unsignedBigInteger('IdDenuncia');
            $table->unsignedBigInteger('IdUsuario');
            $table->enum('StatusAnterior', [1, 2, 3])->comment('1=Pendente, 2=Em análise, 3=Resolvido');
            $table->enum('StatusNovo', [1, 2, 3])->comment('1=Pendente, 2=Em análise, 3=Resolvido');
            $table->dateTime('DataAlteracao')->useCurrent();
            $table->text('Observacao')->nullable();

            $table->foreign('IdDenuncia')->references('IdDenuncia')->on('denuncias')->onDelete('cascade');
            $table->foreign('IdUsuario')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historico_status');
    }
};