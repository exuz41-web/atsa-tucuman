<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CentSede extends Model
{
    protected $table = 'cent_sedes';

    protected $fillable = [
        'nombre',
        'slug',
        'ciudad',
        'direccion',
        'telefono',
        'whatsapp',
        'email',
        'horarios',
        'responsable',
        'imagen',
        'activa',
        'orden',
    ];

    protected function casts(): array
    {
        return [
            'activa' => 'boolean',
            'orden' => 'integer',
        ];
    }

    public function preinscripciones(): HasMany
    {
        return $this->hasMany(PreinscripcionCent::class);
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(MatriculaCent::class);
    }

    public function comisiones(): HasMany
    {
        return $this->hasMany(Comision::class);
    }

    public function carreras(): BelongsToMany
    {
        return $this->belongsToMany(Carrera::class, 'carrera_cent_sede')->withTimestamps();
    }

    public function getImagenUrlAttribute(): ?string
    {
        if (! $this->imagen) {
            return match (true) {
                str_contains(strtolower($this->nombre.' '.$this->ciudad), 'capital'),
                str_contains(strtolower($this->nombre.' '.$this->ciudad), 'san miguel') => asset('images/filiales/central-ciudad-deportiva.jpg'),
                str_contains(strtolower($this->nombre.' '.$this->ciudad), 'banda') => asset('images/filiales/filial-este-banda.jpg'),
                str_contains(strtolower($this->nombre.' '.$this->ciudad), 'concepción'),
                str_contains(strtolower($this->nombre.' '.$this->ciudad), 'concepcion') => asset('images/filiales/filial-sur-concepcion.jpg'),
                default => asset('images/historia/formacion-cent-74.jpg'),
            };
        }

        if (str_starts_with($this->imagen, 'images/')) {
            return asset($this->imagen);
        }

        if (str_starts_with($this->imagen, 'http://') || str_starts_with($this->imagen, 'https://')) {
            return $this->imagen;
        }

        return asset('storage/'.$this->imagen);
    }

    public function getMapsUrlAttribute(): string
    {
        $query = trim(collect([$this->direccion, $this->ciudad, 'Tucumán'])->filter()->implode(', '));

        return 'https://www.google.com/maps/search/?api=1&query='.urlencode($query ?: $this->nombre);
    }

    public function getWhatsappUrlAttribute(): ?string
    {
        $number = preg_replace('/\D+/', '', $this->whatsapp ?: $this->telefono ?: '');

        if (! $number) {
            return null;
        }

        if (! str_starts_with($number, '54')) {
            $number = '54'.$number;
        }

        return 'https://wa.me/'.$number;
    }
}

