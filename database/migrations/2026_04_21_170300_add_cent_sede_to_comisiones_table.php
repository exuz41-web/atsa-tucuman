<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comisiones', function (Blueprint $table) {
            if (! Schema::hasColumn('comisiones', 'cent_sede_id')) {
                $table->foreignId('cent_sede_id')->nullable()->after('filial_id')->constrained('cent_sedes')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('comisiones', function (Blueprint $table) {
            if (Schema::hasColumn('comisiones', 'cent_sede_id')) {
                $table->dropConstrainedForeignId('cent_sede_id');
            }
        });
    }
};
