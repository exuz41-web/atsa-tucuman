<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CentHorario extends Model
{
    protected $table = 'cent_horarios';

    protected $fillable = [
        'titulo',
        'cent_sede_id',
        'carrera_id',
        'ciclo_lectivo',
        'descripcion',
        'archivo',
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

    public function sede(): BelongsTo
    {
        return $this->belongsTo(CentSede::class, 'cent_sede_id');
    }

    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class);
    }

    public function getArchivoUrlAttribute(): ?string
    {
        if (! $this->archivo) {
            return null;
        }

        if (str_starts_with($this->archivo, 'http://') || str_starts_with($this->archivo, 'https://')) {
            return $this->archivo;
        }

        return asset('storage/'.$this->archivo);
    }

    public function getEsImagenAttribute(): bool
    {
        if (! $this->archivo) {
            return false;
        }

        return (bool) preg_match('/\.(jpg|jpeg|png|webp|gif)$/i', $this->archivo);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
