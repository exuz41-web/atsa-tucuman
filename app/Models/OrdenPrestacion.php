<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrdenPrestacion extends Model
{
    protected $table = 'ordenes_prestacion';

    protected $fillable = [
        'codigo',
        'prestador_id',
        'afiliado_id',
        'pedido_id',
        'solicitud_beneficio_id',
        'tipo',
        'estado',
        'detalle',
        'observaciones_internas',
        'respuesta_prestador',
        'emitida_at',
        'aceptada_at',
        'entregada_at',
        'emitida_por',
        'cerrada_por',
    ];

    protected function casts(): array
    {
        return [
            'emitida_at' => 'datetime',
            'aceptada_at' => 'datetime',
            'entregada_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $orden): void {
            $orden->codigo ??= self::generarCodigo();
            $orden->estado ??= 'emitida';
            $orden->emitida_at ??= now();
            $orden->emitida_por ??= auth()->id();
        });
    }

    public function prestador(): BelongsTo
    {
        return $this->belongsTo(Prestador::class);
    }

    public function afiliado(): BelongsTo
    {
        return $this->belongsTo(User::class, 'afiliado_id');
    }

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function solicitudBeneficio(): BelongsTo
    {
        return $this->belongsTo(SolicitudBeneficio::class);
    }

    public function emitidaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'emitida_por');
    }

    public function cerradaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cerrada_por');
    }

    public static function estados(): array
    {
        return [
            'emitida' => 'Emitida',
            'aceptada' => 'Aceptada por prestador',
            'observada' => 'Observada',
            'entregada' => 'Entregada',
            'anulada' => 'Anulada',
        ];
    }

    public static function estadoColor(?string $estado): string
    {
        return match ($estado) {
            'emitida' => 'warning',
            'aceptada' => 'info',
            'observada' => 'warning',
            'entregada' => 'success',
            'anulada' => 'danger',
            default => 'gray',
        };
    }

    public static function tipos(): array
    {
        return [
            'anteojos' => 'Anteojos',
            'medicacion' => 'Medicación',
            'turismo' => 'Turismo',
            'salud' => 'Salud',
            'convenio' => 'Convenio',
            'otro' => 'Otro',
        ];
    }

    public static function generarCodigo(): string
    {
        $nextId = ((int) static::max('id')) + 1;

        return 'ORD-'.now()->format('Y').'-'.str_pad((string) $nextId, 6, '0', STR_PAD_LEFT);
    }
}
