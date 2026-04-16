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
        if (!Schema::hasTable('descarte')) {
            Schema::create('descarte', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_medicamento_doacao')->constrained('medicamentos_doacao')->cascadeOnDelete();
                $table->foreignId('id_entidade')->constrained('entidades')->cascadeOnDelete();
                $table->foreignId('id_farmaceutico_responsavel')->constrained('farmaceuticos')->cascadeOnDelete();
                $table->string('motivo_descarte', 255);
                $table->timestamp('data_descarte')->useCurrent();
                $table->enum('destino_final', [
                    'incinerado',
                    'residuos_especiais',
                    'devolucao_fornecedor',
                    'local_autorizado',
                    'retorno_posto',
                    'autoclave',
                    'outro',
                ]);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('descarte');
    }
};
