<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beneficios', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('slug')->unique();
            $table->enum('categoria', [
                'gremial',
                'accion_social',
                'turismo',
                'formacion',
                'convenios',
                'tramites',
                'salud',
            ])->default('gremial');
            $table->string('descripcion_corta', 500);
            $table->longText('descripcion_larga')->nullable();
            $table->string('imagen')->nullable();
            $table->string('icono')->default('ti-gift');
            $table->text('requisitos')->nullable();
            $table->text('documentacion')->nullable();
            $table->string('link')->nullable();
            $table->boolean('publico')->default(true);
            $table->boolean('solo_afiliados')->default(false);
            $table->boolean('destacado')->default(false);
            $table->boolean('activo')->default(true);
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beneficios');
    }
};
