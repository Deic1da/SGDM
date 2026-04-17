<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('usuarios')) {
            return;
        }

        if (!Schema::hasColumn('usuarios', 'foto_perfil')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->string('foto_perfil', 255)->nullable()->after('estado');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('usuarios')) {
            return;
        }

        if (Schema::hasColumn('usuarios', 'foto_perfil')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->dropColumn('foto_perfil');
            });
        }
    }
};
