<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MesaExamenCent extends Model
{
    protected $table = 'mesa_examen_cents';

    protected $fillable = [
        'materia_id',
        'cent_sede_id',
        'docente_id',
        'fecha',
        'hora',
        'turno',
        'aula',
        'cupo',
        'estado',
        'acta_estado',
        'acta_cerrada_at',
        'acta_cerrada_por',
        'acta_aprobada_at',
        'acta_aprobada_por',
        'acta_libro',
        'acta_folio',
        'acta_observaciones',
        'observaciones',
        'creada_por',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'cupo' => 'integer',
            'acta_cerrada_at' => 'datetime',
            'acta_aprobada_at' => 'datetime',
        ];
    }

    public function materia(): BelongsTo
    {
        return $this->belongsTo(Materia::class);
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(CentSede::class, 'cent_sede_id');
    }

    public function docente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'docente_id');
    }

    public function creadaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creada_por');
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
        return $this->hasMany(InscripcionMesaCent::class);
    }

    public function getTieneCupoAttribute(): bool
    {
        return ! $this->cupo || $this->inscripciones()->where('estado', '!=', 'cancelado')->count() < $this->cupo;
    }
}
