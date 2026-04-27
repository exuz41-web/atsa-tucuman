<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AsistenciaCent extends Model
{
    protected $table = 'asistencia_cents';

    protected $fillable = [
        'comision_id',
        'alumno_id',
        'fecha',
        'estado',
        'observaciones',
        'cargado_por',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
        ];
    }

    public function comision(): BelongsTo
    {
        return $this->belongsTo(Comision::class);
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
