<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carreras', function (Blueprint $table) {
            $table->string('imagen_path')->nullable()->after('requirements');
            $table->string('color')->nullable()->after('imagen_path')->comment('Color de acento para la card, ej: #1e3a5f');
        });
    }

    public function down(): void
    {
        Schema::table('carreras', function (Blueprint $table) {
            $table->dropColumn(['imagen_path', 'color']);
        });
    }
};
