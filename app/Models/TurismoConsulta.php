<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TurismoConsulta extends Model
{
    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'dni',
        'numero_afiliado',
        'beneficio',
        'fecha_estimada',
        'mensaje',
        'estado',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha_estimada' => 'date',
        ];
    }
}
