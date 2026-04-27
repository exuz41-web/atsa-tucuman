<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CentMaterial extends Model
{
    protected $table = 'cent_materiales';

    protected $fillable = [
        'comision_id',
        'clase_id',
        'titulo',
        'descripcion',
        'tipo',
        'archivo',
        'url',
        'publicado',
        'creado_por',
    ];

    protected function casts(): array
    {
        return [
            'publicado' => 'boolean',
        ];
    }

    public function comision(): BelongsTo
    {
        return $this->belongsTo(Comision::class);
    }

    public function clase(): BelongsTo
    {
        return $this->belongsTo(CentClase::class, 'clase_id');
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }
}
