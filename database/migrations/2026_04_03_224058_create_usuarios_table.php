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
        Schema::create('usuarios', function (Blueprint $table) {

            $table->id();
            
            // Dados principais
            $table->string('nome_completo');
            $table->string('cpf', 11)->unique();
            $table->string('email')->unique();
            $table->string('telefone', 15)->nullable();
            
            // Senha
            $table->string('password');
            
            // Datas
            $table->timestamps();

            // Endereço
            $table->string('cep', 9);
            $table->string('logradouro');
            $table->string('numero', 20);
            $table->string('bairro', 100);
            $table->string('municipio', 100);
            $table->char('estado', 2);

            // Status
            $table->enum('status', ['Ativo', 'Inativo'])->default('Ativo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
