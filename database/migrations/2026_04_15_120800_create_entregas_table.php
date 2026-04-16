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
        if (!Schema::hasTable('entregas')) {
            Schema::create('entregas', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_estoque_entidade')->constrained('estoque_entidade')->cascadeOnDelete();
                $table->foreignId('id_farmaceutico_entregador')->constrained('farmaceuticos')->cascadeOnDelete();
                $table->foreignId('id_receptor_pf')->constrained('usuarios')->cascadeOnDelete();
                $table->timestamp('data_entrega')->useCurrent();
                $table->enum('status_entrega', ['Realizada', 'Cancelada']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entregas');
    }
};
