<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestadores', function (Blueprint $table): void {
            $table->id();
            $table->string('nombre');
            $table->string('tipo')->default('otro');
            $table->string('cuit')->nullable();
            $table->string('responsable')->nullable();
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
            $table->string('direccion')->nullable();
            $table->string('localidad')->nullable();
            $table->string('provincia')->nullable();
            $table->text('observaciones')->nullable();
            $table->boolean('activo')->default(true);
            $table->uuid('portal_token')->nullable()->unique();
            $table->timestamps();

            $table->index(['tipo', 'activo']);
        });

        Schema::create('ordenes_prestacion', function (Blueprint $table): void {
            $table->id();
            $table->string('codigo')->unique();
            $table->foreignId('prestador_id')->constrained('prestadores')->restrictOnDelete();
            $table->foreignId('afiliado_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('pedido_id')->nullable()->constrained('pedidos')->nullOnDelete();
            $table->foreignId('solicitud_beneficio_id')->nullable()->constrained('solicitudes_beneficios')->nullOnDelete();
            $table->string('tipo')->default('otro');
            $table->string('estado')->default('emitida');
            $table->text('detalle')->nullable();
            $table->text('observaciones_internas')->nullable();
            $table->text('respuesta_prestador')->nullable();
            $table->timestamp('emitida_at')->nullable();
            $table->timestamp('aceptada_at')->nullable();
            $table->timestamp('entregada_at')->nullable();
            $table->foreignId('emitida_por')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('cerrada_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['prestador_id', 'estado']);
            $table->index(['afiliado_id', 'estado']);
            $table->index(['pedido_id', 'solicitud_beneficio_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordenes_prestacion');
        Schema::dropIfExists('prestadores');
    }
};
