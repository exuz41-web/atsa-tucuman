<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CentDescarga extends Model
{
    protected $table = 'cent_descargas';

    protected $fillable = [
        'titulo',
        'categoria',
        'descripcion',
        'archivo',
        'url_externa',
        'carrera_id',
        'activo',
        'orden',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'orden'  => 'integer',
        ];
    }

    public static function categorias(): array
    {
        return [
            'formularios'    => 'Formularios',
            'reglamentos'    => 'Reglamentos',
            'planes_estudio' => 'Planes de estudio',
            'inscripciones'  => 'Inscripciones',
            'resoluciones'   => 'Resoluciones',
            'otros'          => 'Otros',
        ];
    }

    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class);
    }

    public function getUrlDescargaAttribute(): ?string
    {
        if ($this->url_externa) {
            return $this->url_externa;
        }

        if (! $this->archivo) {
            return null;
        }

        if (str_starts_with($this->archivo, 'http://') || str_starts_with($this->archivo, 'https://')) {
            return $this->archivo;
        }

        return asset('storage/'.$this->archivo);
    }

    public function getCategoriaLabelAttribute(): string
    {
        return static::categorias()[$this->categoria] ?? ucfirst($this->categoria);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
