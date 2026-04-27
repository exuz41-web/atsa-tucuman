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
        Schema::table('users', function (Blueprint $table) {
            $table->string('numero_afiliado')->nullable()->unique()->after('phone');
            $table->string('address')->nullable()->after('numero_afiliado');
            $table->boolean('active')->default(true)->after('address');
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('alumno','docente','coordinador','admin','afiliado') NOT NULL DEFAULT 'alumno'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->where('role', 'afiliado')->update(['role' => 'alumno']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('alumno','docente','coordinador','admin') NOT NULL DEFAULT 'alumno'");
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['numero_afiliado', 'address', 'active']);
        });
    }
};
