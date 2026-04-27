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
        Schema::create('mesa_examen_cents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materia_id')->constrained('materias')->cascadeOnDelete();
            $table->foreignId('cent_sede_id')->nullable()->constrained('cent_sedes')->nullOnDelete();
            $table->foreignId('docente_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('fecha');
            $table->string('hora')->nullable();
            $table->string('turno')->nullable();
            $table->string('aula')->nullable();
            $table->unsignedInteger('cupo')->nullable();
            $table->enum('estado', ['abierta', 'cerrada', 'finalizada'])->default('abierta');
            $table->text('observaciones')->nullable();
            $table->foreignId('creada_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesa_examen_cents');
    }
};
