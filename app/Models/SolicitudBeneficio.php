<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolicitudBeneficio extends Model
{
    protected $table = 'solicitudes_beneficios';

    protected $fillable = [
        'beneficio_id',
        'afiliado_id',
        'mensaje',
        'estado',
        'archivo_dni',
        'archivo_recibo',
        'archivo_adicional',
        'observaciones',
        'respondido_at',
        'respondido_por',
    ];

    protected function casts(): array
    {
        return [
            'respondido_at' => 'datetime',
        ];
    }

    public function beneficio(): BelongsTo
    {
        return $this->belongsTo(Beneficio::class);
    }

    public function afiliado(): BelongsTo
    {
        return $this->belongsTo(User::class, 'afiliado_id');
    }

    public function respondidoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'respondido_por');
    }

    public static function estados(): array
    {
        return [
            'pendiente' => 'Pendiente',
            'en_revision' => 'En revisión',
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
}
