<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Efemeride extends Model
{
    protected $table = 'efemerides';

    protected $fillable = [
        'titulo',
        'descripcion',
        'dia',
        'mes',
        'anio',
        'imagen_path',
        'fuente',
        'fuente_url',
        'enlace_externo',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'dia'    => 'integer',
            'mes'    => 'integer',
            'activo' => 'boolean',
        ];
    }

    public function getImagenUrlAttribute(): ?string
    {
        if (!$this->imagen_path) return null;
        return \Illuminate\Support\Facades\Storage::disk('public')->url($this->imagen_path);
    }
}
