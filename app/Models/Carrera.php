<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Carrera extends Model
{
    protected $table = 'carreras';

    protected $fillable = [
        'name',
        'slug',
        'duration',
        'title_granted',
        'description',
        'requirements',
        'imagen_path',
        'color',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    // Colores por defecto según slug de carrera
    protected static array $defaultColors = [
        'enfermeria-profesional'                         => '#c0392b',
        'tec-sup-en-agente-socio-sanitario'              => '#1e8e6e',
        'tec-sup-en-diagnostico-por-imagenes'            => '#1565c0',
        'tec-sup-en-farmacia'                            => '#6a1b9a',
        'tec-sup-en-laboratorio-de-analisis-clinicos'    => '#e65100',
        'tec-sup-en-esterilizacion'                      => '#00838f',
    ];

    protected static array $defaultIcons = [
        'enfermeria-profesional'                         => 'ti-heart-rate-monitor',
        'tec-sup-en-agente-socio-sanitario'              => 'ti-heart-handshake',
        'tec-sup-en-diagnostico-por-imagenes'            => 'ti-scan',
        'tec-sup-en-farmacia'                            => 'ti-vaccine',
        'tec-sup-en-laboratorio-de-analisis-clinicos'    => 'ti-microscope',
        'tec-sup-en-esterilizacion'                      => 'ti-shield-check',
    ];

    public function getImagenUrlAttribute(): ?string
    {
        if (! $this->imagen_path) {
            return null;
        }

        if (str_starts_with($this->imagen_path, 'http://') || str_starts_with($this->imagen_path, 'https://')) {
            return $this->imagen_path;
        }

        return asset('storage/'.$this->imagen_path);
    }

    public function getColorAttribute($value): string
    {
        return $value ?: (static::$defaultColors[$this->slug] ?? '#1e3a5f');
    }

    public function getIconAttribute(): string
    {
        return static::$defaultIcons[$this->slug] ?? 'ti-school';
    }

    public function materias(): HasMany
    {
        return $this->hasMany(Materia::class);
    }

    public function filiales(): BelongsToMany
    {
        return $this->belongsToMany(Filial::class, 'carrera_filial')->withTimestamps();
    }

    public function centSedes(): BelongsToMany
    {
        return $this->belongsToMany(CentSede::class, 'carrera_cent_sede')->withTimestamps();
    }
}
