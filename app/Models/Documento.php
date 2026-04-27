<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $table = 'documentos';

    protected $fillable = [
        'titulo',
        'tipo',
        'archivo',
        'anio',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'anio' => 'integer',
        ];
    }
}
