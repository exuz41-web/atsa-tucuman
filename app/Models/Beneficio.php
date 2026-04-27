<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Beneficio extends Model
{
    protected $fillable = [
        'titulo',
        'slug',
        'categoria',
        'descripcion_corta',
        'descripcion_larga',
        'imagen',
        'icono',
        'requisitos',
        'documentacion',
        'link',
        'publico',
        'solo_afiliados',
        'destacado',
        'activo',
        'orden',
    ];

    protected function casts(): array
    {
        return [
            'publico' => 'boolean',
            'solo_afiliados' => 'boolean',
            'destacado' => 'boolean',
            'activo' => 'boolean',
            'orden' => 'integer',
        ];
    }

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('activo', true);
    }

    public function scopePublicos(Builder $query): Builder
    {
        return $query->where('publico', true);
    }

    public function scopeOrdenados(Builder $query): Builder
    {
        return $query->orderBy('orden')->orderBy('titulo');
    }

    public function getImagenUrlAttribute(): ?string
    {
        if (! $this->imagen) {
            return null;
        }

        if (str_starts_with($this->imagen, 'http://') || str_starts_with($this->imagen, 'https://')) {
            return $this->imagen;
        }

        if (str_starts_with($this->imagen, 'images/') || str_starts_with($this->imagen, 'modernize/')) {
            return asset($this->imagen);
        }

        return Storage::disk('public')->url($this->imagen);
    }

    public static function categorias(): array
    {
        return [
            'gremial' => 'Gremial',
            'accion_social' => 'Acción social',
            'turismo' => 'Turismo y recreación',
            'formacion' => 'Formación',
            'convenios' => 'Convenios y descuentos',
            'tramites' => 'Trámites',
            'salud' => 'Salud y asistencia',
        ];
    }

    public static function colores(): array
    {
        return [
            'gremial' => 'primary',
            'accion_social' => 'danger',
            'turismo' => 'info',
            'formacion' => 'success',
            'convenios' => 'warning',
            'tramites' => 'gray',
            'salud' => 'success',
        ];
    }
}
