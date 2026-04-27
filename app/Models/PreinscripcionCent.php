<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreinscripcionCent extends Model
{
    protected $table = 'preinscripciones_cent';

    protected $fillable = [
        'codigo',
        'user_id',
        'carrera_id',
        'cent_sede_id',
        'ciclo_lectivo',
        'apellido_nombre',
        'fecha_nacimiento',
        'nacionalidad',
        'estado_civil',
        'tipo_documento',
        'dni',
        'email',
        'telefono',
        'domicilio',
        'localidad',
        'establecimiento_laboral',
        'nivel_estudios',
        'titulo_secundario',
        'observaciones_alumno',
        'archivo_dni',
        'archivo_titulo',
        'archivo_recibo',
        'archivo_adicional',
        'estado',
        'observaciones_admin',
        'aprobado_por',
        'aprobado_at',
    ];

    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
            'aprobado_at' => 'datetime',
            'ciclo_lectivo' => 'integer',
        ];
    }

    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class);
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(CentSede::class, 'cent_sede_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function aprobador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }
}
