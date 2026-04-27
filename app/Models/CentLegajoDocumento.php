<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CentLegajoDocumento extends Model
{
    protected $table = 'cent_legajo_documentos';

    protected $fillable = [
        'user_id',
        'tipo',
        'archivo',
        'estado',
        'observaciones',
        'validado_por',
        'validado_at',
    ];

    protected function casts(): array
    {
        return [
            'validado_at' => 'datetime',
        ];
    }

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function validador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validado_por');
    }

    public static function tipos(): array
    {
        return [
            'dni' => 'DNI',
            'titulo_secundario' => 'Título secundario',
            'acta_nacimiento' => 'Acta de nacimiento',
            'vacuna_hepatitis_b' => 'Carnet vacuna hepatitis B',
            'psicofisico' => 'Aptitud psicofísica',
            'residencia' => 'Certificado de residencia',
            'foto' => 'Foto',
            'otro' => 'Otro',
        ];
    }
}
