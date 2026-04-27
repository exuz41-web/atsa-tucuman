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
        Schema::table('users', function (Blueprint $table) {
            $table->string('foto_perfil')->nullable()->after('categoria_laboral');
            $table->boolean('carnet_activo')->default(true)->after('foto_perfil');
            $table->date('carnet_vencimiento')->nullable()->default(now()->endOfYear()->toDateString())->after('carnet_activo');
            $table->timestamp('carnet_emitido_at')->nullable()->after('carnet_vencimiento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'foto_perfil',
                'carnet_activo',
                'carnet_vencimiento',
                'carnet_emitido_at',
            ]);
        });
    }
};
