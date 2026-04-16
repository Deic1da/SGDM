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
        if (!Schema::hasTable('estoque_entidade')) {
            Schema::create('estoque_entidade', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_entidade')->constrained('entidades')->cascadeOnDelete();
                $table->foreignId('id_validacao')->constrained('validacoes')->cascadeOnDelete();
                $table->integer('quantidade');
                $table->timestamp('data_entrada')->useCurrent();
                $table->enum('status_estoque', ['Disponivel', 'Reservado', 'Entregue', 'Descartado']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estoque_entidade');
    }
};
