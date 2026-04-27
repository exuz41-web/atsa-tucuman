<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'perfil_interno',
        'cargo_interno',
        'puede_ver_todas_las_filiales',
        'cent_role',
        'filial_id',
        'secretaria_id',
        'establecimiento_id',
        'cent_sede_id',
        'dni',
        'phone',
        'numero_afiliado',
        'address',
        'active',
        'estado_afiliado',
        'tipo_afiliado',
        'es_delegado_gremial',
        'es_congresal',
        'fecha_alta',
        'obra_social',
        'lugar_trabajo',
        'categoria_laboral',
        'legajo_laboral',
        'foto_perfil',
        'carnet_activo',
        'carnet_vencimiento',
        'carnet_emitido_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        // Usuarios inactivos no pueden acceder a ningún panel
        if ($this->active === false) {
            return false;
        }

        if ($panel->getId() === 'cent') {
            // Panel CENT N°74: usa cent_role con fallback a role (igual que el middleware)
            $centRole = $this->cent_role ?: $this->role;
            return in_array($centRole, ['admin', 'coordinador', 'directivo', 'docente', 'alumno'], true);
        }

        // Panel ATSA admin
        if ($this->role === 'admin') {
            return true;
        }

        if ($this->puede_ver_todas_las_filiales) {
            return true;
        }

        if (filled($this->perfil_interno) && $this->perfil_interno !== 'ninguno') {
            return true;
        }

        return false;
    }

    public function filial(): BelongsTo
    {
        return $this->belongsTo(Filial::class);
    }

    public function secretaria(): BelongsTo
    {
        return $this->belongsTo(Secretaria::class);
    }

    public function establecimiento(): BelongsTo
    {
        return $this->belongsTo(Establecimiento::class);
    }

    public function centSede(): BelongsTo
    {
        return $this->belongsTo(CentSede::class, 'cent_sede_id');
    }

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class, 'afiliado_id');
    }

    public function consultas(): HasMany
    {
        return $this->hasMany(Consulta::class, 'afiliado_id');
    }

    public function testimonios(): HasMany
    {
        return $this->hasMany(Testimonio::class, 'afiliado_id');
    }

    public function matriculasCent(): HasMany
    {
        return $this->hasMany(MatriculaCent::class, 'user_id');
    }

    public function comisionesDocente(): HasMany
    {
        return $this->hasMany(Comision::class, 'docente_id');
    }

    public function inscripcionesAcademicas(): HasMany
    {
        return $this->hasMany(Inscripcion::class, 'alumno_id');
    }

    public function notasAcademicas(): HasMany
    {
        return $this->hasMany(Nota::class, 'alumno_id');
    }

    public function asistenciasCent(): HasMany
    {
        return $this->hasMany(AsistenciaCent::class, 'alumno_id');
    }

    public function legajoCent(): HasMany
    {
        return $this->hasMany(CentLegajoDocumento::class, 'user_id');
    }

    public function cuotasCent(): HasMany
    {
        return $this->hasMany(CentCuota::class, 'alumno_id');
    }

    public function equivalenciasCent(): HasMany
    {
        return $this->hasMany(CentEquivalencia::class, 'alumno_id');
    }

    public function inscripcionesMesasCent(): HasMany
    {
        return $this->hasMany(InscripcionMesaCent::class, 'alumno_id');
    }

    public function entregasTrabajosCent(): HasMany
    {
        return $this->hasMany(CentEntregaTrabajo::class, 'alumno_id');
    }

    public function permisosExamenCent(): HasMany
    {
        return $this->hasMany(CentPermisoExamen::class, 'alumno_id');
    }

    public function notificacionesCent(): HasMany
    {
        return $this->hasMany(CentNotificacion::class, 'user_id');
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'active' => 'boolean',
            'puede_ver_todas_las_filiales' => 'boolean',
            'es_delegado_gremial' => 'boolean',
            'es_congresal' => 'boolean',
            'fecha_alta' => 'date',
            'carnet_activo' => 'boolean',
            'carnet_vencimiento' => 'date',
            'carnet_emitido_at' => 'datetime',
        ];
    }
}
