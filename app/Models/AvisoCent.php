<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AvisoCent extends Model
{
    protected $table = 'aviso_cents';

    protected $fillable = [
        'titulo',
        'contenido',
        'tipo',
        'rol_destino',
        'carrera_id',
        'cent_sede_id',
        'publicado_desde',
        'publicado_hasta',
        'destacado',
        'publico',
        'imagen_path',
        'gallery',
        'video_url',
        'adjunto_path',
        'activo',
        'creado_por',
    ];

    protected function casts(): array
    {
        return [
            'publicado_desde' => 'datetime',
            'publicado_hasta' => 'datetime',
            'destacado'       => 'boolean',
            'publico'         => 'boolean',
            'activo'          => 'boolean',
            'gallery'         => 'array',
        ];
    }

    // Colores e íconos por tipo
    public static array $tipoConfig = [
        'aviso'        => ['color' => 'primary',  'icon' => 'ti-speakerphone',    'label' => 'Aviso'],
        'noticia'      => ['color' => 'info',      'icon' => 'ti-news',            'label' => 'Noticia'],
        'mesa'         => ['color' => 'warning',   'icon' => 'ti-writing',         'label' => 'Mesa de examen'],
        'inscripcion'  => ['color' => 'success',   'icon' => 'ti-user-plus',       'label' => 'Inscripción'],
        'regularidad'  => ['color' => 'danger',    'icon' => 'ti-alert-triangle',  'label' => 'Regularidad'],
        'calendario'   => ['color' => 'secondary', 'icon' => 'ti-calendar-event',  'label' => 'Calendario'],
    ];

    public function getTipoConfigAttribute(): array
    {
        return static::$tipoConfig[$this->tipo] ?? static::$tipoConfig['aviso'];
    }

    public function getImagenUrlAttribute(): ?string
    {
        if (! $this->imagen_path) {
            return null;
        }
        if (str_starts_with($this->imagen_path, 'http')) {
            return $this->imagen_path;
        }
        return asset('storage/'.$this->imagen_path);
    }

    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class);
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(CentSede::class, 'cent_sede_id');
    }

    public function creadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    /** Scope: activos y dentro del rango de fecha de publicación */
    public function scopeVigentes(Builder $query): Builder
    {
        return $query
            ->where('activo', true)
            ->where(fn ($q) => $q->whereNull('publicado_desde')->orWhere('publicado_desde', '<=', now()))
            ->where(fn ($q) => $q->whereNull('publicado_hasta')->orWhere('publicado_hasta', '>=', now()));
    }

    /** Scope: visibles en la web pública sin login */
    public function scopePublicos(Builder $query): Builder
    {
        return $query->where('publico', true);
    }
}
