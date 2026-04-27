<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Secretaria extends Model
{
    protected $table = 'secretarias';

    protected $fillable = [
        'nombre',
        'slug',
        'responsable',
        'telefono',
        'email',
        'descripcion',
        'orden',
        'activa',
    ];

    protected function casts(): array
    {
        return [
            'activa' => 'boolean',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class);
    }

    public function solicitudesBeneficios(): HasMany
    {
        return $this->hasMany(SolicitudBeneficio::class);
    }
}
