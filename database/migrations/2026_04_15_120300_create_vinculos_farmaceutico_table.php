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
        if (!Schema::hasTable('vinculos_farmaceutico')) {
            Schema::create('vinculos_farmaceutico', function (Blueprint $table) {
                $table->id('id_vinculo_farmaceutico');
                $table->foreignId('id_entidade')->constrained('entidades')->cascadeOnDelete();
                $table->foreignId('id_farmaceutico')->constrained('farmaceuticos')->cascadeOnDelete();
                $table->enum('tipo_vinculo', ['Responsavel_Tecnico', 'Farmaceutico_Normal'])->default('Responsavel_Tecnico');
                $table->enum('status_vinculo', ['Pendente', 'Aceito', 'Removido'])->default('Pendente');
                $table->date('date_inicio');
                $table->date('data_fim')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vinculos_farmaceutico');
    }
};
