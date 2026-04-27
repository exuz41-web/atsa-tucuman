<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aviso_cents', function (Blueprint $table) {
            $table->boolean('publico')->default(false)->after('destacado')
                  ->comment('Si es true, visible en la web pública sin login');
            $table->string('tipo')->default('aviso')->after('publico')
                  ->comment('aviso, mesa, noticia, inscripcion, regularidad');
            $table->string('imagen_path')->nullable()->after('tipo');
        });

        Schema::table('cent_eventos', function (Blueprint $table) {
            $table->boolean('publico')->default(false)->after('activo')
                  ->comment('Si es true, visible en la web pública sin login');
        });
    }

    public function down(): void
    {
        Schema::table('aviso_cents', function (Blueprint $table) {
            $table->dropColumn(['publico', 'tipo', 'imagen_path']);
        });
        Schema::table('cent_eventos', function (Blueprint $table) {
            $table->dropColumn('publico');
        });
    }
};
