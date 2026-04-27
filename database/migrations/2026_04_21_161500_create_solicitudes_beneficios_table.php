<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes_beneficios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beneficio_id')->constrained('beneficios')->cascadeOnDelete();
            $table->foreignId('afiliado_id')->constrained('users')->cascadeOnDelete();
            $table->text('mensaje');
            $table->enum('estado', ['pendiente', 'en_revision', 'aprobada', 'rechazada', 'entregada'])->default('pendiente');
            $table->string('archivo_dni')->nullable();
            $table->string('archivo_recibo')->nullable();
            $table->string('archivo_adicional')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamp('respondido_at')->nullable();
            $table->foreignId('respondido_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes_beneficios');
    }
};
