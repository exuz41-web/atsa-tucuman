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
        Schema::create('tramites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['constancia', 'titulo', 'libre', 'analitico', 'otro']);
            $table->enum('status', ['pendiente', 'en_proceso', 'listo', 'entregado'])->default('pendiente');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tramites');
    }
};
