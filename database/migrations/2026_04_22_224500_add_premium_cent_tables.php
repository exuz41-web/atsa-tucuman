<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'cent_sede_id')) {
                $table->foreignId('cent_sede_id')->nullable()->after('filial_id')->constrained('cent_sedes')->nullOnDelete();
            }
        });

        Schema::table('matriculas_cent', function (Blueprint $table) {
            if (! Schema::hasColumn('matriculas_cent', 'regularidad_vencimiento')) {
                $table->date('regularidad_vencimiento')->nullable()->after('fecha_ingreso');
            }
        });

        if (! Schema::hasTable('cent_eventos')) {
            Schema::create('cent_eventos', function (Blueprint $table) {
                $table->id();
                $table->string('titulo');
                $table->text('descripcion')->nullable();
                $table->enum('tipo', ['clase', 'mesa', 'inscripcion', 'feriado', 'parcial', 'evento', 'otro'])->default('evento');
                $table->dateTime('fecha_inicio');
                $table->dateTime('fecha_fin')->nullable();
                $table->foreignId('cent_sede_id')->nullable()->constrained('cent_sedes')->nullOnDelete();
                $table->foreignId('carrera_id')->nullable()->constrained('carreras')->nullOnDelete();
                $table->enum('rol_destino', ['todos', 'alumno', 'docente', 'coordinador', 'directivo'])->default('todos');
                $table->boolean('activo')->default(true);
                $table->foreignId('creado_por')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('cent_legajo_documentos')) {
            Schema::create('cent_legajo_documentos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->enum('tipo', ['dni', 'titulo_secundario', 'acta_nacimiento', 'vacuna_hepatitis_b', 'psicofisico', 'residencia', 'foto', 'otro']);
                $table->string('archivo')->nullable();
                $table->enum('estado', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
                $table->text('observaciones')->nullable();
                $table->foreignId('validado_por')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('validado_at')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('cent_cuotas')) {
            Schema::create('cent_cuotas', function (Blueprint $table) {
                $table->id();
                $table->foreignId('matricula_cent_id')->nullable()->constrained('matriculas_cent')->nullOnDelete();
                $table->foreignId('alumno_id')->constrained('users')->cascadeOnDelete();
                $table->string('concepto');
                $table->string('periodo')->nullable();
                $table->decimal('monto', 12, 2)->default(0);
                $table->enum('descuento_tipo', ['ninguno', 'afiliado_atsa', 'hijo_afiliado_atsa', 'beca', 'otro'])->default('ninguno');
                $table->decimal('descuento_porcentaje', 5, 2)->default(0);
                $table->decimal('descuento_monto', 12, 2)->default(0);
                $table->foreignId('afiliado_descuento_id')->nullable()->constrained('users')->nullOnDelete();
                $table->decimal('monto_final', 12, 2)->default(0);
                $table->date('vencimiento')->nullable();
                $table->enum('estado', ['pendiente', 'pagada', 'vencida', 'bonificada', 'anulada'])->default('pendiente');
                $table->string('comprobante')->nullable();
                $table->timestamp('pagado_at')->nullable();
                $table->text('observaciones')->nullable();
                $table->foreignId('creado_por')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('cent_equivalencias')) {
            Schema::create('cent_equivalencias', function (Blueprint $table) {
                $table->id();
                $table->foreignId('alumno_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('materia_id')->constrained('materias')->cascadeOnDelete();
                $table->string('institucion_origen')->nullable();
                $table->decimal('nota', 4, 2)->nullable();
                $table->enum('estado', ['solicitada', 'aprobada', 'rechazada'])->default('solicitada');
                $table->text('observaciones')->nullable();
                $table->foreignId('aprobado_por')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('aprobado_at')->nullable();
                $table->timestamps();
                $table->unique(['alumno_id', 'materia_id']);
            });
        }

        if (! Schema::hasTable('cent_activity_logs')) {
            Schema::create('cent_activity_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('accion');
                $table->string('modelo')->nullable();
                $table->unsignedBigInteger('modelo_id')->nullable();
                $table->text('descripcion')->nullable();
                $table->string('ip', 64)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cent_activity_logs');
        Schema::dropIfExists('cent_equivalencias');
        Schema::dropIfExists('cent_cuotas');
        Schema::dropIfExists('cent_legajo_documentos');
        Schema::dropIfExists('cent_eventos');

        Schema::table('matriculas_cent', function (Blueprint $table) {
            if (Schema::hasColumn('matriculas_cent', 'regularidad_vencimiento')) {
                $table->dropColumn('regularidad_vencimiento');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'cent_sede_id')) {
                $table->dropConstrainedForeignId('cent_sede_id');
            }
        });
    }
};
