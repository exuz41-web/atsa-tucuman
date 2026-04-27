<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cent_horarios', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->foreignId('cent_sede_id')->nullable()->constrained('cent_sedes')->nullOnDelete();
            $table->foreignId('carrera_id')->nullable()->constrained('carreras')->nullOnDelete();
            $table->string('ciclo_lectivo', 20)->nullable();
            $table->longText('descripcion')->nullable();
            $table->string('archivo')->nullable();
            $table->boolean('activo')->default(true);
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cent_horarios');
    }
};
