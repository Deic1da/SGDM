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
        if (!Schema::hasTable('validacoes')) {
            Schema::create('validacoes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_medicamento_doacao')->constrained('medicamentos_doacao')->cascadeOnDelete();
                $table->foreignId('id_farmaceutico_validante')->constrained('farmaceuticos')->cascadeOnDelete();
                $table->enum('status_validacao', ['Aprovado', 'Rejeitado', 'Em Processo']);
                $table->string('motivo_rejeicao', 255)->nullable();
                $table->timestamp('data_validacao')->useCurrent();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('validacoes');
    }
};
