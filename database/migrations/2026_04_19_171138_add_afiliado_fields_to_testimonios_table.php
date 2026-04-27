<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('testimonios', function (Blueprint $table) {
            $table->foreignId('afiliado_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente')->after('activo');
        });

        DB::table('testimonios')
            ->where('activo', true)
            ->update(['estado' => 'aprobado']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonios', function (Blueprint $table) {
            $table->dropForeign(['afiliado_id']);
            $table->dropColumn(['afiliado_id', 'estado']);
        });
    }
};
