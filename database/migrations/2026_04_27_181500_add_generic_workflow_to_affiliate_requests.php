<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE pedidos MODIFY estado ENUM('pendiente', 'en_revision', 'observado', 'aprobado', 'rechazado', 'entregado') NOT NULL DEFAULT 'pendiente'");
            DB::statement("ALTER TABLE solicitudes_beneficios MODIFY estado ENUM('pendiente', 'en_revision', 'observada', 'aprobada', 'rechazada', 'entregada') NOT NULL DEFAULT 'pendiente'");
        }

        Schema::table('pedidos', function (Blueprint $table): void {
            $table->foreignId('secretaria_id')->nullable()->after('tipo')->constrained('secretarias')->nullOnDelete();
            $table->foreignId('asignado_a')->nullable()->after('secretaria_id')->constrained('users')->nullOnDelete();
            $table->foreignId('derivado_por')->nullable()->after('asignado_a')->constrained('users')->nullOnDelete();
            $table->text('observacion_afiliado')->nullable()->after('observaciones');
            $table->timestamp('aprobado_at')->nullable()->after('observacion_afiliado');
            $table->timestamp('entregado_at')->nullable()->after('aprobado_at');
            $table->index(['secretaria_id', 'estado']);
        });

        Schema::table('solicitudes_beneficios', function (Blueprint $table): void {
            $table->foreignId('secretaria_id')->nullable()->after('beneficio_id')->constrained('secretarias')->nullOnDelete();
            $table->foreignId('asignado_a')->nullable()->after('secretaria_id')->constrained('users')->nullOnDelete();
            $table->foreignId('derivado_por')->nullable()->after('asignado_a')->constrained('users')->nullOnDelete();
            $table->text('observacion_afiliado')->nullable()->after('observaciones');
            $table->timestamp('aprobado_at')->nullable()->after('respondido_por');
            $table->timestamp('entregado_at')->nullable()->after('aprobado_at');
            $table->index(['secretaria_id', 'estado']);
        });

        Schema::create('expediente_movimientos', function (Blueprint $table): void {
            $table->id();
            $table->string('expediente_type');
            $table->unsignedBigInteger('expediente_id');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('secretaria_origen_id')->nullable()->constrained('secretarias')->nullOnDelete();
            $table->foreignId('secretaria_destino_id')->nullable()->constrained('secretarias')->nullOnDelete();
            $table->string('estado_anterior')->nullable();
            $table->string('estado_nuevo')->nullable();
            $table->text('observacion_interna')->nullable();
            $table->text('observacion_afiliado')->nullable();
            $table->timestamps();

            $table->index(['expediente_type', 'expediente_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expediente_movimientos');

        Schema::table('solicitudes_beneficios', function (Blueprint $table): void {
            $table->dropIndex(['secretaria_id', 'estado']);
            $table->dropConstrainedForeignId('secretaria_id');
            $table->dropConstrainedForeignId('asignado_a');
            $table->dropConstrainedForeignId('derivado_por');
            $table->dropColumn(['observacion_afiliado', 'aprobado_at', 'entregado_at']);
        });

        Schema::table('pedidos', function (Blueprint $table): void {
            $table->dropIndex(['secretaria_id', 'estado']);
            $table->dropConstrainedForeignId('secretaria_id');
            $table->dropConstrainedForeignId('asignado_a');
            $table->dropConstrainedForeignId('derivado_por');
            $table->dropColumn(['observacion_afiliado', 'aprobado_at', 'entregado_at']);
        });

        DB::table('pedidos')->where('estado', 'observado')->update(['estado' => 'pendiente']);
        DB::table('solicitudes_beneficios')->where('estado', 'observada')->update(['estado' => 'pendiente']);

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE pedidos MODIFY estado ENUM('pendiente', 'en_revision', 'aprobado', 'rechazado', 'entregado') NOT NULL DEFAULT 'pendiente'");
            DB::statement("ALTER TABLE solicitudes_beneficios MODIFY estado ENUM('pendiente', 'en_revision', 'aprobada', 'rechazada', 'entregada') NOT NULL DEFAULT 'pendiente'");
        }
    }
};
