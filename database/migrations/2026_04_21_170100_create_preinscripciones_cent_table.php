<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preinscripciones_cent', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('carrera_id')->constrained('carreras')->cascadeOnDelete();
            $table->foreignId('cent_sede_id')->constrained('cent_sedes')->cascadeOnDelete();
            $table->year('ciclo_lectivo');
            $table->string('apellido_nombre');
            $table->date('fecha_nacimiento')->nullable();
            $table->string('nacionalidad')->nullable();
            $table->string('estado_civil')->nullable();
            $table->string('tipo_documento')->default('DNI');
            $table->string('dni');
            $table->string('email');
            $table->string('telefono')->nullable();
            $table->string('domicilio')->nullable();
            $table->string('localidad')->nullable();
            $table->string('establecimiento_laboral')->nullable();
            $table->string('nivel_estudios')->nullable();
            $table->string('titulo_secundario')->nullable();
            $table->text('observaciones_alumno')->nullable();
            $table->string('archivo_dni')->nullable();
            $table->string('archivo_titulo')->nullable();
            $table->string('archivo_recibo')->nullable();
            $table->string('archivo_adicional')->nullable();
            $table->enum('estado', ['pendiente', 'en_revision', 'aprobada', 'rechazada', 'inscripta'])->default('pendiente');
            $table->text('observaciones_admin')->nullable();
            $table->foreignId('aprobado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('aprobado_at')->nullable();
            $table->timestamps();

            $table->index(['dni', 'ciclo_lectivo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preinscripciones_cent');
    }
};
