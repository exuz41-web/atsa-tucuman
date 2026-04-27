<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matriculas_cent', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('carrera_id')->constrained('carreras')->cascadeOnDelete();
            $table->foreignId('cent_sede_id')->constrained('cent_sedes')->cascadeOnDelete();
            $table->string('legajo')->unique();
            $table->year('ciclo_lectivo');
            $table->enum('estado', ['preinscripto', 'inscripto', 'cursando', 'regular', 'egresado', 'baja'])->default('preinscripto');
            $table->date('fecha_ingreso')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'carrera_id', 'ciclo_lectivo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matriculas_cent');
    }
};
