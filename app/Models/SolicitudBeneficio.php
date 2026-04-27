<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SolicitudBeneficio extends Model
{
    protected $table = 'solicitudes_beneficios';

    protected $fillable = [
        'beneficio_id',
        'secretaria_id',
        'asignado_a',
        'derivado_por',
        'afiliado_id',
        'mensaje',
        'estado',
        'archivo_dni',
        'archivo_recibo',
        'archivo_adicional',
        'observaciones',
        'observacion_afiliado',
        'respondido_at',
        'respondido_por',
        'aprobado_at',
        'entregado_at',
    ];

    protected function casts(): array
    {
        return [
            'respondido_at' => 'datetime',
            'aprobado_at' => 'datetime',
            'entregado_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $solicitud): void {
            $solicitud->estado ??= 'pendiente';

            if (! $solicitud->secretaria_id && $solicitud->beneficio_id) {
                $categoria = Beneficio::whereKey($solicitud->beneficio_id)->value('categoria');
                $solicitud->secretaria_id = self::secretariaSugeridaId($categoria);
            }
        });

        static::updated(function (self $solicitud): void {
            if (! $solicitud->wasChanged(['estado', 'secretaria_id', 'asignado_a', 'observaciones', 'observacion_afiliado'])) {
                return;
            }

            $solicitud->movimientos()->create([
                'user_id' => auth()->id(),
                'secretaria_origen_id' => $solicitud->getOriginal('secretaria_id'),
                'secretaria_destino_id' => $solicitud->secretaria_id,
                'estado_anterior' => $solicitud->getOriginal('estado'),
                'estado_nuevo' => $solicitud->estado,
                'observacion_interna' => $solicitud->observaciones,
                'observacion_afiliado' => $solicitud->observacion_afiliado,
            ]);
        });
    }

    public function beneficio(): BelongsTo
    {
        return $this->belongsTo(Beneficio::class);
    }

    public function afiliado(): BelongsTo
    {
        return $this->belongsTo(User::class, 'afiliado_id');
    }

    public function secretaria(): BelongsTo
    {
        return $this->belongsTo(Secretaria::class);
    }

    public function asignadoA(): BelongsTo
    {
        return $this->belongsTo(User::class, 'asignado_a');
    }

    public function derivadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'derivado_por');
    }

    public function respondidoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'respondido_por');
    }

    public function movimientos(): MorphMany
    {
        return $this->morphMany(ExpedienteMovimiento::class, 'expediente')->latest();
    }

    public static function estados(): array
    {
        return [
            'pendiente' => 'Pendiente',
            'en_revision' => 'En revisión',
            'observada' => 'Falta documentación',
            'aprobada' => 'Aprobada',
            'rechazada' => 'Rechazada',
            'entregada' => 'Entregada',
        ];
    }

    public static function estadoColor(?string $estado): string
    {
        return match ($estado) {
            'pendiente' => 'warning',
            'en_revision' => 'info',
            'observada' => 'warning',
            'aprobada' => 'success',
            'rechazada' => 'danger',
            'entregada' => 'gray',
            default => 'gray',
        };
    }

    public static function numero(int $id): string
    {
        return 'BEN-'.str_pad((string) $id, 6, '0', STR_PAD_LEFT);
    }

    public static function secretariaSugeridaId(?string $categoria): ?int
    {
        $slug = match ($categoria) {
            'accion_social', 'salud', 'convenios' => 'secretaria-de-prevision-y-accion-social',
            'turismo' => 'secretaria-de-turismo-y-vivienda',
            'formacion' => 'secretaria-de-capacitacion-y-formacion-profesional',
            'gremial' => 'secretaria-gremial',
            'tramites' => 'secretaria-general',
            default => null,
        };

        return $slug ? Secretaria::where('slug', $slug)->value('id') : null;
    }
}
