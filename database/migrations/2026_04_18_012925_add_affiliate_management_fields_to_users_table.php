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
            $table->enum('estado_afiliado', ['activo', 'suspendido', 'baja'])->default('activo')->after('active');
            $table->date('fecha_alta')->nullable()->after('estado_afiliado');
            $table->string('obra_social')->nullable()->after('fecha_alta');
            $table->string('lugar_trabajo')->nullable()->after('obra_social');
            $table->string('categoria_laboral')->nullable()->after('lugar_trabajo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'estado_afiliado',
                'fecha_alta',
                'obra_social',
                'lugar_trabajo',
                'categoria_laboral',
            ]);
        });
    }
};
