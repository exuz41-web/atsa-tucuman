<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HotelConvenio extends Model
{
    protected $table = 'hoteles_convenio';

    protected $fillable = [
        'nombre',
        'tipo',
        'localidad',
        'provincia',
        'direccion',
        'descripcion',
        'imagen',
        'mapa_url',
        'web_url',
        'activo',
        'orden',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'orden' => 'integer',
        ];
    }

    public function getImagenUrlAttribute(): ?string
    {
        if (! $this->imagen) {
            return null;
        }

        if (str_starts_with($this->imagen, 'images/') || str_starts_with($this->imagen, 'modernize/')) {
            return asset($this->imagen);
        }

        if (str_starts_with($this->imagen, 'http://') || str_starts_with($this->imagen, 'https://')) {
            return $this->imagen;
        }

        return Storage::disk('public')->url($this->imagen);
    }
}
