<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Materia extends Model
{
    protected $table = 'materias';

    protected $fillable = [
        'carrera_id',
        'name',
        'year',
        'semester',
        'hours',
        'correlatives',
    ];

    protected function casts(): array
    {
        return [
            'correlatives' => 'array',
        ];
    }

    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class);
    }

    public function comisiones(): HasMany
    {
        return $this->hasMany(Comision::class);
    }

    public function mesasExamen(): HasMany
    {
        return $this->hasMany(MesaExamenCent::class);
    }
}
