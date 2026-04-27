<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('comisiones', 'filial_id')) {
            return;
        }

        Schema::table('comisiones', function (Blueprint $table) {
            $table->dropForeign(['filial_id']);
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE comisiones MODIFY filial_id BIGINT UNSIGNED NULL');
        } else {
            Schema::table('comisiones', function (Blueprint $table) {
                $table->foreignId('filial_id')->nullable()->change();
            });
        }

        Schema::table('comisiones', function (Blueprint $table) {
            $table->foreign('filial_id')->references('id')->on('filiales')->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('comisiones', 'filial_id')) {
            return;
        }

        Schema::table('comisiones', function (Blueprint $table) {
            $table->dropForeign(['filial_id']);
        });

        DB::table('comisiones')->whereNull('filial_id')->update(['filial_id' => 1]);

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE comisiones MODIFY filial_id BIGINT UNSIGNED NOT NULL');
        } else {
            Schema::table('comisiones', function (Blueprint $table) {
                $table->foreignId('filial_id')->nullable(false)->change();
            });
        }

        Schema::table('comisiones', function (Blueprint $table) {
            $table->foreign('filial_id')->references('id')->on('filiales')->cascadeOnDelete();
        });
    }
};
