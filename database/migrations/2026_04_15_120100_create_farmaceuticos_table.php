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
        if (!Schema::hasTable('farmaceuticos')) {
            Schema::create('farmaceuticos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_usuario_pf')->unique()->constrained('usuarios')->cascadeOnDelete();
                $table->string('num_crf', 20)->unique();
                $table->enum('uf_crf', [
                    'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS',
                    'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC',
                    'SP', 'SE', 'TO',
                ]);
                $table->enum('status_profissional', ['Ativo', 'Suspenso', 'Inativo'])->default('Ativo');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmaceuticos');
    }
};
