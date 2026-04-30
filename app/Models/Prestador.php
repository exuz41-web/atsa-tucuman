<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Prestador extends Model
{
    protected $table = 'prestadores';

    protected $fillable = [
        'nombre',
        'tipo',
        'cuit',
        'responsable',
        'email',
        'telefono',
        'direccion',
        'localidad',
        'provincia',
        'observaciones',
        'activo',
        'portal_token',
        'portal_username',
        'portal_password',
        'portal_last_login_at',
    ];

    protected $hidden = [
        'portal_password',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'portal_last_login_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $prestador): void {
            $prestador->portal_token ??= (string) Str::uuid();
            $prestador->portal_username ??= self::generarPortalUsername($prestador->nombre, $prestador->id);
        });
    }

    public function ordenesPrestacion(): HasMany
    {
        return $this->hasMany(OrdenPrestacion::class);
    }

    public function portalUrl(): string
    {
        return route('prestadores.portal', $this->portal_token);
    }

    public function loginUrl(): string
    {
        return route('prestadores.login');
    }

    public function tieneAccesoPortal(): bool
    {
        return filled($this->portal_token)
            && filled($this->portal_username)
            && filled($this->portal_password);
    }

    /**
     * Devuelve la contraseña plana solo cuando se acaba de generar.
     */
    public function asegurarAccesoPortal(?string $password = null, bool $resetPassword = false): array
    {
        $data = [];
        $plainPassword = null;

        if (blank($this->portal_token)) {
            $data['portal_token'] = (string) Str::uuid();
        }

        if (blank($this->portal_username)) {
            $data['portal_username'] = self::generarPortalUsername($this->nombre, $this->id);
        }

        if ($resetPassword || blank($this->portal_password)) {
            $plainPassword = $password ?: self::generarPortalPassword();
            $data['portal_password'] = Hash::make($plainPassword);
        }

        if ($data !== []) {
            $this->forceFill($data)->save();
            $this->refresh();
        }

        return [
            'nombre' => $this->nombre,
            'usuario' => $this->portal_username,
            'password' => $plainPassword,
            'login' => $this->loginUrl(),
            'portal' => $this->portalUrl(),
        ];
    }

    public static function generarPortalPassword(): string
    {
        return 'Atsa'.Str::random(8).random_int(10, 99).'!';
    }

    public static function generarPortalUsername(string $nombre, ?int $ignoreId = null): string
    {
        $base = Str::of($nombre)
            ->ascii()
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', '-')
            ->trim('-')
            ->limit(34, '')
            ->toString();

        $base = $base !== '' ? $base : 'prestador';
        $candidate = $base;
        $suffix = 2;

        while (self::query()
            ->where('portal_username', $candidate)
            ->when($ignoreId, fn ($query, int $id) => $query->whereKeyNot($id))
            ->exists()) {
            $candidate = Str::limit($base, 30, '').'-'.$suffix;
            $suffix++;
        }

        return $candidate;
    }

    public static function tipos(): array
    {
        return [
            'optica' => 'Óptica',
            'farmacia' => 'Farmacia',
            'hotel' => 'Hotel / alojamiento',
            'comercio' => 'Comercio',
            'salud' => 'Prestador de salud',
            'servicio' => 'Servicio',
            'otro' => 'Otro',
        ];
    }
}
