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
        Schema::create('consultas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('afiliado_id')->constrained('users')->cascadeOnDelete();
            $table->enum('tipo', ['turno', 'consulta']);
            $table->string('asunto');
            $table->text('mensaje');
            $table->date('fecha_solicitada')->nullable();
            $table->enum('estado', ['pendiente', 'confirmado', 'rechazado', 'completado'])->default('pendiente');
            $table->text('respuesta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultas');
    }
};
