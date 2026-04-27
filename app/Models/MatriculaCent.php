<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatriculaCent extends Model
{
    protected $table = 'matriculas_cent';

    protected $fillable = [
        'user_id',
        'carrera_id',
        'cent_sede_id',
        'legajo',
        'ciclo_lectivo',
        'estado',
        'fecha_ingreso',
        'regularidad_vencimiento',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha_ingreso' => 'date',
            'regularidad_vencimiento' => 'date',
            'ciclo_lectivo' => 'integer',
        ];
    }

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class);
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(CentSede::class, 'cent_sede_id');
    }
}
