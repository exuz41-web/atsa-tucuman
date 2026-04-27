<?php

namespace App\Models;

use App\Support\CarnetSupport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Testimonio extends Model
{
    protected $table = 'testimonios';

    protected $fillable = [
        'afiliado_id',
        'nombre',
        'cargo',
        'filial',
        'texto',
        'foto',
        'activo',
        'estado',
        'orden',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'orden' => 'integer',
        ];
    }

    public function afiliado(): BelongsTo
    {
        return $this->belongsTo(User::class, 'afiliado_id');
    }

    public function getFotoUrlAttribute(): ?string
    {
        if (! $this->foto) {
            return $this->afiliado ? CarnetSupport::fotoUrl($this->afiliado) : null;
        }

        if (str_starts_with($this->foto, 'images/')) {
            return asset($this->foto);
        }

        return Storage::disk('public')->url($this->foto);
    }

    public function getInicialesAttribute(): string
    {
        return collect(explode(' ', trim($this->nombre)))
            ->filter()
            ->take(2)
            ->map(fn (string $part): string => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('');
    }
}
