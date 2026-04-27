<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CentClase extends Model
{
    protected $table = 'cent_clases';

    protected $fillable = [
        'comision_id',
        'titulo',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'modalidad',
        'aula',
        'link_virtual',
        'publicada',
        'creado_por',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'datetime',
            'fecha_fin' => 'datetime',
            'publicada' => 'boolean',
        ];
    }

    public function comision(): BelongsTo
    {
        return $this->belongsTo(Comision::class);
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function materiales(): HasMany
    {
        return $this->hasMany(CentMaterial::class, 'clase_id');
    }
}
