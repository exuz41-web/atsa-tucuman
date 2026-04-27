<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Pedido extends Model
{
    protected $table = 'pedidos';

    protected $fillable = [
        'afiliado_id',
        'tipo',
        'secretaria_id',
        'asignado_a',
        'derivado_por',
        'descripcion',
        'estado',
        'archivo_dni',
        'archivo_recibo',
        'archivo_adicional',
        'observaciones',
        'observacion_afiliado',
        'aprobado_at',
        'entregado_at',
    ];

    protected function casts(): array
    {
        return [
            'aprobado_at' => 'datetime',
            'entregado_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $pedido): void {
            $pedido->estado ??= 'pendiente';
            $pedido->secretaria_id ??= self::secretariaSugeridaId($pedido->tipo);
        });

        static::updated(function (self $pedido): void {
            if (! $pedido->wasChanged(['estado', 'secretaria_id', 'asignado_a', 'observaciones', 'observacion_afiliado'])) {
                return;
            }

            $pedido->movimientos()->create([
                'user_id' => auth()->id(),
                'secretaria_origen_id' => $pedido->getOriginal('secretaria_id'),
                'secretaria_destino_id' => $pedido->secretaria_id,
                'estado_anterior' => $pedido->getOriginal('estado'),
                'estado_nuevo' => $pedido->estado,
                'observacion_interna' => $pedido->observaciones,
                'observacion_afiliado' => $pedido->observacion_afiliado,
            ]);
        });
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

    public function movimientos(): MorphMany
    {
        return $this->morphMany(ExpedienteMovimiento::class, 'expediente')->latest();
    }

    public function ordenesPrestacion(): HasMany
    {
        return $this->hasMany(OrdenPrestacion::class);
    }

    public static function secretariaSugeridaId(?string $tipo): ?int
    {
        $slug = match ($tipo) {
            'anteojos', 'medicacion', 'medicamentos', 'ayuda_social', 'ayuda_economica', 'subsidio', 'bolson', 'kit_escolar', 'nacimiento' => 'secretaria-de-prevision-y-accion-social',
            'turismo' => 'secretaria-de-turismo-y-vivienda',
            'tramite' => 'secretaria-general',
            default => null,
        };

        return $slug ? Secretaria::where('slug', $slug)->value('id') : null;
    }

    public static function estados(): array
    {
        return [
            'pendiente' => 'Pendiente',
            'en_revision' => 'En revisión',
            'observado' => 'Falta documentación',
            'aprobado' => 'Aprobado',
            'rechazado' => 'Rechazado',
            'entregado' => 'Entregado',
        ];
    }

    public static function estadoColor(?string $estado): string
    {
        return match ($estado) {
            'pendiente' => 'warning',
            'en_revision' => 'info',
            'observado' => 'warning',
            'aprobado' => 'success',
            'rechazado' => 'danger',
            'entregado' => 'gray',
            default => 'gray',
        };
    }

    public static function numero(int $id): string
    {
        return 'PED-'.str_pad((string) $id, 6, '0', STR_PAD_LEFT);
    }
}
