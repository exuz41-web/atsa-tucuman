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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('afiliado_id')->constrained('users')->cascadeOnDelete();
            $table->enum('tipo', ['anteojos', 'protesis', 'medicamentos', 'ayuda_economica', 'otro']);
            $table->text('descripcion');
            $table->enum('estado', ['pendiente', 'en_revision', 'aprobado', 'rechazado', 'entregado'])->default('pendiente');
            $table->string('archivo_dni')->nullable();
            $table->string('archivo_recibo')->nullable();
            $table->string('archivo_adicional')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
