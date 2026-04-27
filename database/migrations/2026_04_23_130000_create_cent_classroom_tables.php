<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cent_clases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comision_id')->constrained('comisiones')->cascadeOnDelete();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin')->nullable();
            $table->enum('modalidad', ['presencial', 'virtual', 'mixta'])->default('presencial');
            $table->string('aula')->nullable();
            $table->string('link_virtual')->nullable();
            $table->boolean('publicada')->default(true);
            $table->foreignId('creado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('cent_materiales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comision_id')->constrained('comisiones')->cascadeOnDelete();
            $table->foreignId('clase_id')->nullable()->constrained('cent_clases')->nullOnDelete();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->enum('tipo', ['apunte', 'video', 'link', 'presentacion', 'guia', 'otro'])->default('apunte');
            $table->string('archivo')->nullable();
            $table->string('url')->nullable();
            $table->boolean('publicado')->default(true);
            $table->foreignId('creado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('cent_trabajos_practicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comision_id')->constrained('comisiones')->cascadeOnDelete();
            $table->string('titulo');
            $table->text('consigna');
            $table->dateTime('fecha_publicacion')->nullable();
            $table->dateTime('fecha_entrega')->nullable();
            $table->decimal('puntaje_maximo', 5, 2)->nullable();
            $table->string('archivo_consigna')->nullable();
            $table->boolean('acepta_entregas')->default(true);
            $table->boolean('publicado')->default(true);
            $table->foreignId('creado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('cent_entregas_trabajos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trabajo_practico_id')->constrained('cent_trabajos_practicos')->cascadeOnDelete();
            $table->foreignId('alumno_id')->constrained('users')->cascadeOnDelete();
            $table->text('comentario')->nullable();
            $table->string('archivo')->nullable();
            $table->enum('estado', ['entregado', 'observado', 'aprobado', 'desaprobado'])->default('entregado');
            $table->decimal('calificacion', 4, 2)->nullable();
            $table->text('devolucion')->nullable();
            $table->timestamp('entregado_at')->nullable();
            $table->timestamp('corregido_at')->nullable();
            $table->foreignId('corregido_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['trabajo_practico_id', 'alumno_id']);
        });

        Schema::create('cent_permisos_examen', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->foreignId('alumno_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('mesa_examen_cent_id')->constrained('mesa_examen_cents')->cascadeOnDelete();
            $table->foreignId('cent_cuota_id')->nullable()->constrained('cent_cuotas')->nullOnDelete();
            $table->enum('estado', ['pendiente_pago', 'habilitado', 'usado', 'anulado'])->default('pendiente_pago');
            $table->decimal('monto', 10, 2)->default(0);
            $table->string('qr_token')->unique();
            $table->timestamp('habilitado_at')->nullable();
            $table->timestamp('usado_at')->nullable();
            $table->foreignId('habilitado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cent_permisos_examen');
        Schema::dropIfExists('cent_entregas_trabajos');
        Schema::dropIfExists('cent_trabajos_practicos');
        Schema::dropIfExists('cent_materiales');
        Schema::dropIfExists('cent_clases');
    }
};
