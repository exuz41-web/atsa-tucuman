<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class SolicitudAfiliacion extends Model
{
    protected $table = 'solicitudes_afiliacion';

    protected $fillable = [
        'estado',
        'apellido_nombre',
        'fecha_nacimiento',
        'nacionalidad',
        'estado_civil',
        'tipo_documento',
        'numero_documento',
        'establecimiento',
        'condicion_institucion',
        'nivel',
        'legajo',
        'profesion',
        'domicilio',
        'telefono',
        'email',
        'filial_preferida',
        'nombre_afiliador',
        'celular_afiliador',
        'dni_frente',
        'dni_dorso',
        'recibo_sueldo',
        'formulario_firmado',
        'archivo_adicional',
        'pdf_path',
        'acepta_declaracion',
        'observaciones_admin',
        'reviewed_by',
        'user_id',
        'numero_afiliado_generado',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
            'acepta_declaracion' => 'boolean',
            'reviewed_at' => 'datetime',
        ];
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function archivoUrl(?string $campo): ?string
    {
        if (! $campo) {
            return null;
        }

        if (str_starts_with($campo, 'images/') || str_starts_with($campo, 'modernize/')) {
            return asset($campo);
        }

        return Storage::disk('public')->url($campo);
    }

    public function getEstadoLabelAttribute(): string
    {
        return match ($this->estado) {
            'en_revision' => 'En revisión',
            'observada' => 'Observada',
            'aprobada' => 'Aprobada',
            'rechazada' => 'Rechazada',
            default => 'Pendiente',
        };
    }
}
