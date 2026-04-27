<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Autoridad extends Model
{
    protected $table = 'autoridades';

    protected $fillable = [
        'nombre',
        'cargo',
        'foto',
        'descripcion',
        'orden',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'orden' => 'integer',
    ];

    public function getFotoUrlAttribute(): string
    {
        if ($this->foto) {
            if (str_starts_with($this->foto, 'images/')) {
                return asset($this->foto);
            }

            return Storage::disk('public')->url($this->foto);
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($this->nombre).'&size=200&background=1e3a5f&color=fff';
    }
}
