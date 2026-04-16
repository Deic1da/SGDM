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
        if (!Schema::hasColumn('usuarios', 'ultimo_acesso')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->timestamp('ultimo_acesso')->nullable()->after('password');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('usuarios', 'ultimo_acesso')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->dropColumn('ultimo_acesso');
            });
        }
    }
};
