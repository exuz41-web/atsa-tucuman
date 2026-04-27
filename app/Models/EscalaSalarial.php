<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EscalaSalarial extends Model
{
    protected $table = 'escalas_salariales';

    protected $fillable = [
        'titulo',
        'descripcion',
        'archivo',
        'vigente_desde',
        'vigente_hasta',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'vigente_desde' => 'date',
            'vigente_hasta' => 'date',
            'activo' => 'boolean',
        ];
    }
}
