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
        if (!Schema::hasTable('entidades')) {
            Schema::create('entidades', function (Blueprint $table) {
                $table->id();
                $table->string('razao_social');
                $table->string('nome_fantasia')->nullable();
                $table->string('cnpj', 18)->unique();
                $table->foreignId('id_dono_entidade')->constrained('usuarios')->cascadeOnDelete();
                $table->string('horario_funcionamento', 100);
                $table->boolean('aceita_validade_curta');
                $table->enum('status', ['Pendente', 'Aprovado', 'Reprovado', 'Inativo'])->default('Pendente');
                $table->timestamp('data_cadastro')->useCurrent();
                $table->double('latitude');
                $table->double('longitude');
                $table->foreignId('farmaceutico_rt')->unique()->nullable()->constrained('farmaceuticos')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entidades');
    }
};
