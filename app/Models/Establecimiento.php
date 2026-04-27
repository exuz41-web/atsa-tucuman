<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Establecimiento extends Model
{
    protected $table = 'establecimientos';

    protected $fillable = [
        'nombre',
        'slug',
        'tipo',
        'sector',
        'filial_id',
        'direccion',
        'localidad',
        'telefono',
        'email',
        'responsable',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function filial(): BelongsTo
    {
        return $this->belongsTo(Filial::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
