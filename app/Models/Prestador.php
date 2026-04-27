<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Prestador extends Model
{
    protected $table = 'prestadores';

    protected $fillable = [
        'nombre',
        'tipo',
        'cuit',
        'responsable',
        'email',
        'telefono',
        'direccion',
        'localidad',
        'provincia',
        'observaciones',
        'activo',
        'portal_token',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $prestador): void {
            $prestador->portal_token ??= (string) Str::uuid();
        });
    }

    public function ordenesPrestacion(): HasMany
    {
        return $this->hasMany(OrdenPrestacion::class);
    }

    public function portalUrl(): string
    {
        return route('prestadores.portal', $this->portal_token);
    }

    public static function tipos(): array
    {
        return [
            'optica' => 'Óptica',
            'farmacia' => 'Farmacia',
            'hotel' => 'Hotel / alojamiento',
            'comercio' => 'Comercio',
            'salud' => 'Prestador de salud',
            'servicio' => 'Servicio',
            'otro' => 'Otro',
        ];
    }
}
