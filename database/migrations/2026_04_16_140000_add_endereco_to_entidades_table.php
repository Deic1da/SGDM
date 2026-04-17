<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('entidades')) {
            return;
        }

        Schema::table('entidades', function (Blueprint $table) {
            if (!Schema::hasColumn('entidades', 'cep')) {
                $table->string('cep', 9)->nullable()->after('horario_funcionamento');
            }

            if (!Schema::hasColumn('entidades', 'logradouro')) {
                $table->string('logradouro')->nullable()->after('cep');
            }

            if (!Schema::hasColumn('entidades', 'numero')) {
                $table->string('numero', 20)->nullable()->after('logradouro');
            }

            if (!Schema::hasColumn('entidades', 'bairro')) {
                $table->string('bairro', 100)->nullable()->after('numero');
            }

            if (!Schema::hasColumn('entidades', 'municipio')) {
                $table->string('municipio', 100)->nullable()->after('bairro');
            }

            if (!Schema::hasColumn('entidades', 'estado')) {
                $table->char('estado', 2)->nullable()->after('municipio');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('entidades')) {
            return;
        }

        Schema::table('entidades', function (Blueprint $table) {
            $columns = ['cep', 'logradouro', 'numero', 'bairro', 'municipio', 'estado'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('entidades', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
