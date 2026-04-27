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
        Schema::create('turismo_consultas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('email')->nullable();
            $table->string('telefono');
            $table->string('dni')->nullable();
            $table->string('numero_afiliado')->nullable();
            $table->enum('beneficio', ['ciudad_deportiva', 'hotel_termas', 'convenios_fatsa', 'otro'])->default('ciudad_deportiva');
            $table->date('fecha_estimada')->nullable();
            $table->text('mensaje');
            $table->enum('estado', ['pendiente', 'contactado', 'resuelto', 'cancelado'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turismo_consultas');
    }
};
