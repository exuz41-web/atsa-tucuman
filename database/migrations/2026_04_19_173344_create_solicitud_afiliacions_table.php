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
        Schema::create('solicitudes_afiliacion', function (Blueprint $table) {
            $table->id();
            $table->enum('estado', ['pendiente', 'en_revision', 'observada', 'aprobada', 'rechazada'])->default('pendiente');
            $table->string('apellido_nombre');
            $table->date('fecha_nacimiento')->nullable();
            $table->string('nacionalidad')->nullable();
            $table->string('estado_civil')->nullable();
            $table->string('tipo_documento')->default('DNI');
            $table->string('numero_documento');
            $table->string('establecimiento')->nullable();
            $table->string('condicion_institucion')->nullable();
            $table->string('nivel')->nullable();
            $table->string('legajo')->nullable();
            $table->string('profesion')->nullable();
            $table->string('domicilio')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('filial_preferida')->nullable();
            $table->string('nombre_afiliador')->nullable();
            $table->string('celular_afiliador')->nullable();
            $table->string('dni_frente')->nullable();
            $table->string('dni_dorso')->nullable();
            $table->string('recibo_sueldo')->nullable();
            $table->string('formulario_firmado')->nullable();
            $table->string('archivo_adicional')->nullable();
            $table->boolean('acepta_declaracion')->default(false);
            $table->text('observaciones_admin')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['estado', 'created_at']);
            $table->index('numero_documento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_afiliacion');
    }
};
