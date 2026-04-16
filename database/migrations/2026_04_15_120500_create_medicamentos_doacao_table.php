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
        if (!Schema::hasTable('medicamentos_doacao')) {
            Schema::create('medicamentos_doacao', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_doador')->constrained('usuarios')->cascadeOnDelete();
                $table->string('nome_medicamento');
                $table->string('forma_farmaceutica', 100);
                $table->string('condicao_embalagem', 100);
                $table->date('data_validade');
                $table->enum('status_doacao', ['Cadastrado', 'Aprovado', 'Descartado'])->default('Cadastrado');
                $table->timestamp('data_cadastro')->useCurrent();
                $table->foreignId('id_entidade_destino')->nullable()->constrained('entidades')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicamentos_doacao');
    }
};
