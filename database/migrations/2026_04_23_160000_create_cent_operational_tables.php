<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cent_notificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('cent_sede_id')->nullable()->constrained('cent_sedes')->nullOnDelete();
            $table->string('titulo');
            $table->text('mensaje');
            $table->enum('tipo', ['info', 'cuota', 'legajo', 'aula', 'permiso', 'mesa', 'sistema'])->default('info');
            $table->string('url')->nullable();
            $table->timestamp('leida_at')->nullable();
            $table->timestamps();
        });

        Schema::create('cent_recibos', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->foreignId('cent_cuota_id')->constrained('cent_cuotas')->cascadeOnDelete();
            $table->foreignId('alumno_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('monto', 10, 2);
            $table->string('concepto');
            $table->string('periodo')->nullable();
            $table->string('qr_token')->unique();
            $table->timestamp('emitido_at')->nullable();
            $table->foreignId('emitido_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('cent_configuraciones', function (Blueprint $table) {
            $table->id();
            $table->string('clave')->unique();
            $table->text('valor')->nullable();
            $table->enum('tipo', ['texto', 'numero', 'email', 'telefono', 'url', 'imagen', 'booleano'])->default('texto');
            $table->string('grupo')->default('general');
            $table->string('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cent_configuraciones');
        Schema::dropIfExists('cent_recibos');
        Schema::dropIfExists('cent_notificaciones');
    }
};
