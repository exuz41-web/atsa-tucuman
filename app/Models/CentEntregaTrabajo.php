<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CentEntregaTrabajo extends Model
{
    protected $table = 'cent_entregas_trabajos';

    protected $fillable = [
        'trabajo_practico_id',
        'alumno_id',
        'comentario',
        'archivo',
        'estado',
        'calificacion',
        'devolucion',
        'entregado_at',
        'corregido_at',
        'corregido_por',
    ];

    protected function casts(): array
    {
        return [
            'calificacion' => 'decimal:2',
            'entregado_at' => 'datetime',
            'corregido_at' => 'datetime',
        ];
    }

    public function trabajo(): BelongsTo
    {
        return $this->belongsTo(CentTrabajoPractico::class, 'trabajo_practico_id');
    }

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(User::class, 'alumno_id');
    }

    public function corrector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'corregido_por');
    }
}
