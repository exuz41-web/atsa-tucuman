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
        Schema::create('notas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('comision_id')->constrained('comisiones')->cascadeOnDelete();
            $table->enum('type', ['parcial1', 'parcial2', 'final', 'recuperatorio']);
            $table->decimal('grade', 4, 2)->nullable();
            $table->enum('status', ['aprobado', 'desaprobado', 'ausente', 'libre']);
            $table->foreignId('loaded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notas');
    }
};
