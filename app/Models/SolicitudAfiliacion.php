<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        'public_token',
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

    protected static function booted(): void
    {
        static::creating(function (self $solicitud): void {
            $solicitud->public_token ??= (string) Str::uuid();
        });
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

        return $this->storageDiskFor($campo)->url($campo);
    }

    public function storageDiskFor(?string $path): FilesystemAdapter
    {
        $disk = $this->storageDiskNameFor($path);

        return Storage::disk($disk);
    }

    public function storageDiskNameFor(?string $path): string
    {
        if (! $path) {
            return 'local';
        }

        if (Storage::disk('local')->exists($path)) {
            return 'local';
        }

        return 'public';
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
