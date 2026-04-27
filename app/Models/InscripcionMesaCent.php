<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InscripcionMesaCent extends Model
{
    protected $table = 'inscripcion_mesa_cents';

    protected $fillable = [
        'mesa_examen_cent_id',
        'alumno_id',
        'estado',
        'nota',
        'observaciones',
        'cargado_por',
    ];

    protected function casts(): array
    {
        return [
            'nota' => 'decimal:2',
        ];
    }

    public function mesa(): BelongsTo
    {
        return $this->belongsTo(MesaExamenCent::class, 'mesa_examen_cent_id');
    }

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(User::class, 'alumno_id');
    }

    public function cargadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cargado_por');
    }
}
