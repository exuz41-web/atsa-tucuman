<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CentEvento extends Model
{
    protected $table = 'cent_eventos';

    protected $fillable = [
        'titulo',
        'descripcion',
        'tipo',
        'fecha_inicio',
        'fecha_fin',
        'cent_sede_id',
        'carrera_id',
        'rol_destino',
        'activo',
        'publico',
        'creado_por',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'datetime',
            'fecha_fin'    => 'datetime',
            'activo'       => 'boolean',
            'publico'      => 'boolean',
        ];
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(CentSede::class, 'cent_sede_id');
    }

    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class);
    }

    public function creadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }
}
