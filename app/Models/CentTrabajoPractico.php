<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CentTrabajoPractico extends Model
{
    protected $table = 'cent_trabajos_practicos';

    protected $fillable = [
        'comision_id',
        'titulo',
        'consigna',
        'fecha_publicacion',
        'fecha_entrega',
        'puntaje_maximo',
        'archivo_consigna',
        'acepta_entregas',
        'publicado',
        'creado_por',
    ];

    protected function casts(): array
    {
        return [
            'fecha_publicacion' => 'datetime',
            'fecha_entrega' => 'datetime',
            'puntaje_maximo' => 'decimal:2',
            'acepta_entregas' => 'boolean',
            'publicado' => 'boolean',
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

    public function entregas(): HasMany
    {
        return $this->hasMany(CentEntregaTrabajo::class, 'trabajo_practico_id');
    }
}
