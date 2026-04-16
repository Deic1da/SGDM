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
        if (!Schema::hasTable('log_auditoria')) {
            Schema::create('log_auditoria', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_ator_responsavel')->constrained('usuarios')->cascadeOnDelete();
                $table->enum('tipo_operacao', ['Validacao', 'Entrega', 'Alteracao', 'Cadastro'])->default('Validacao');
                $table->string('registro_afetado', 50);
                $table->string('tabela_afetada', 50);
                $table->text('descricao_alteracao')->nullable();
                $table->timestamp('data_hora')->useCurrent();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_auditoria');
    }
};
