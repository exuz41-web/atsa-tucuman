<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inscripcion_mesa_cents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mesa_examen_cent_id')->constrained('mesa_examen_cents')->cascadeOnDelete();
            $table->foreignId('alumno_id')->constrained('users')->cascadeOnDelete();
            $table->enum('estado', ['inscripto', 'cancelado', 'presente', 'ausente', 'aprobado', 'desaprobado'])->default('inscripto');
            $table->decimal('nota', 4, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->foreignId('cargado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['mesa_examen_cent_id', 'alumno_id'], 'mesa_alumno_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscripcion_mesa_cents');
    }
};
