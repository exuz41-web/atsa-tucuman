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
        Schema::create('hoteles_convenio', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->enum('tipo', ['hotel', 'apart_hotel', 'hosteria', 'complejo', 'camping'])->default('hotel');
            $table->string('localidad');
            $table->string('provincia');
            $table->string('direccion')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('imagen')->nullable();
            $table->string('mapa_url')->nullable();
            $table->string('web_url')->nullable();
            $table->boolean('activo')->default(true);
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hoteles_convenio');
    }
};
