<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mesa_examen_cents', function (Blueprint $table) {
            if (! Schema::hasColumn('mesa_examen_cents', 'acta_estado')) {
                $table->enum('acta_estado', ['abierta', 'cerrada', 'aprobada'])->default('abierta')->after('estado');
            }

            if (! Schema::hasColumn('mesa_examen_cents', 'acta_cerrada_at')) {
                $table->timestamp('acta_cerrada_at')->nullable()->after('acta_estado');
            }

            if (! Schema::hasColumn('mesa_examen_cents', 'acta_cerrada_por')) {
                $table->foreignId('acta_cerrada_por')->nullable()->after('acta_cerrada_at')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('mesa_examen_cents', 'acta_aprobada_at')) {
                $table->timestamp('acta_aprobada_at')->nullable()->after('acta_cerrada_por');
            }

            if (! Schema::hasColumn('mesa_examen_cents', 'acta_aprobada_por')) {
                $table->foreignId('acta_aprobada_por')->nullable()->after('acta_aprobada_at')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('mesa_examen_cents', 'acta_libro')) {
                $table->string('acta_libro')->nullable()->after('acta_aprobada_por');
            }

            if (! Schema::hasColumn('mesa_examen_cents', 'acta_folio')) {
                $table->string('acta_folio')->nullable()->after('acta_libro');
            }

            if (! Schema::hasColumn('mesa_examen_cents', 'acta_observaciones')) {
                $table->text('acta_observaciones')->nullable()->after('acta_folio');
            }
        });
    }

    public function down(): void
    {
        Schema::table('mesa_examen_cents', function (Blueprint $table) {
            foreach ([
                'acta_observaciones',
                'acta_folio',
                'acta_libro',
                'acta_aprobada_por',
                'acta_aprobada_at',
                'acta_cerrada_por',
                'acta_cerrada_at',
                'acta_estado',
            ] as $column) {
                if (Schema::hasColumn('mesa_examen_cents', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
