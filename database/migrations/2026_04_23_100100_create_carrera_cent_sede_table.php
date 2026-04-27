<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carrera_cent_sede', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carrera_id')->constrained('carreras')->cascadeOnDelete();
            $table->foreignId('cent_sede_id')->constrained('cent_sedes')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['carrera_id', 'cent_sede_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carrera_cent_sede');
    }
};
