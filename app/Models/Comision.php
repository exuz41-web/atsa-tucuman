<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comision extends Model
{
    protected $table = 'comisiones';

    protected $fillable = [
        'materia_id',
        'filial_id',
        'cent_sede_id',
        'docente_id',
        'year_cycle',
        'schedule',
        'acta_estado',
        'acta_cerrada_at',
        'acta_cerrada_por',
        'acta_aprobada_at',
        'acta_aprobada_por',
        'acta_observaciones',
    ];

    protected function casts(): array
    {
        return [
            'acta_cerrada_at' => 'datetime',
            'acta_aprobada_at' => 'datetime',
        ];
    }

    public function materia(): BelongsTo
    {
        return $this->belongsTo(Materia::class);
    }

    public function filial(): BelongsTo
    {
        return $this->belongsTo(Filial::class);
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(CentSede::class, 'cent_sede_id');
    }

    public function docente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'docente_id');
    }

    public function cerradaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'acta_cerrada_por');
    }

    public function aprobadaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'acta_aprobada_por');
    }

    public function inscripciones(): HasMany
    {
        return $this->hasMany(Inscripcion::class);
    }

    public function notas(): HasMany
    {
        return $this->hasMany(Nota::class);
    }

    public function asistencias(): HasMany
    {
        return $this->hasMany(AsistenciaCent::class);
    }

    public function clases(): HasMany
    {
        return $this->hasMany(CentClase::class);
    }

    public function materiales(): HasMany
    {
        return $this->hasMany(CentMaterial::class);
    }

    public function trabajosPracticos(): HasMany
    {
        return $this->hasMany(CentTrabajoPractico::class);
    }
}
