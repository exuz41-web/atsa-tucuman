<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('secretaria_id')->nullable()->after('filial_id')->constrained('secretarias')->nullOnDelete();
            $table->foreignId('establecimiento_id')->nullable()->after('secretaria_id')->constrained('establecimientos')->nullOnDelete();
            $table->enum('tipo_afiliado', ['estatal', 'privado'])->nullable()->after('estado_afiliado');
            $table->boolean('es_delegado_gremial')->default(false)->after('tipo_afiliado');
            $table->boolean('es_congresal')->default(false)->after('es_delegado_gremial');
            $table->string('legajo_laboral')->nullable()->after('categoria_laboral');
            $table->enum('perfil_interno', ['ninguno', 'recepcion', 'secretaria', 'responsable_filial', 'padron', 'consulta', 'gerencia_general'])
                ->default('ninguno')
                ->after('role');
            $table->string('cargo_interno')->nullable()->after('perfil_interno');
            $table->boolean('puede_ver_todas_las_filiales')->default(false)->after('cargo_interno');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('secretaria_id');
            $table->dropConstrainedForeignId('establecimiento_id');
            $table->dropColumn([
                'tipo_afiliado',
                'es_delegado_gremial',
                'es_congresal',
                'legajo_laboral',
                'perfil_interno',
                'cargo_interno',
                'puede_ver_todas_las_filiales',
            ]);
        });
    }
};
