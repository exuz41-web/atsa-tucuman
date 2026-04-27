<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comisiones', function (Blueprint $table) {
            if (! Schema::hasColumn('comisiones', 'acta_estado')) {
                $table->string('acta_estado')->default('abierta')->after('schedule')->index();
            }

            if (! Schema::hasColumn('comisiones', 'acta_cerrada_at')) {
                $table->timestamp('acta_cerrada_at')->nullable()->after('acta_estado');
            }

            if (! Schema::hasColumn('comisiones', 'acta_cerrada_por')) {
                $table->foreignId('acta_cerrada_por')->nullable()->after('acta_cerrada_at')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('comisiones', 'acta_aprobada_at')) {
                $table->timestamp('acta_aprobada_at')->nullable()->after('acta_cerrada_por');
            }

            if (! Schema::hasColumn('comisiones', 'acta_aprobada_por')) {
                $table->foreignId('acta_aprobada_por')->nullable()->after('acta_aprobada_at')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('comisiones', 'acta_observaciones')) {
                $table->text('acta_observaciones')->nullable()->after('acta_aprobada_por');
            }
        });
    }

    public function down(): void
    {
        Schema::table('comisiones', function (Blueprint $table) {
            foreach (['acta_cerrada_por', 'acta_aprobada_por'] as $column) {
                if (Schema::hasColumn('comisiones', $column)) {
                    $table->dropForeign([$column]);
                }
            }

            $columns = [
                'acta_estado',
                'acta_cerrada_at',
                'acta_cerrada_por',
                'acta_aprobada_at',
                'acta_aprobada_por',
                'acta_observaciones',
            ];

            $existing = array_filter($columns, fn ($column) => Schema::hasColumn('comisiones', $column));

            if ($existing !== []) {
                $table->dropColumn($existing);
            }
        });
    }
};
