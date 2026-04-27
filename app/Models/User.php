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
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

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
        'cent_public_token',
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
            return $this->hasAnyPermission(['cent.portal.use', 'cent.aula.manage', 'cent.alumnos.manage', 'cent.administracion.manage', 'cent.config.manage']);
        }

        return $this->hasAnyPermission(['admin.*', 'admin.padron.manage', 'admin.afiliacion.manage', 'admin.atencion.manage', 'admin.finanzas.manage', 'admin.editor.manage', 'admin.institucion.manage', 'admin.settings.manage']);
    }

    protected static function booted(): void
    {
        static::creating(function (self $user): void {
            if (in_array($user->cent_role ?: $user->role, ['alumno'], true)) {
                $user->cent_public_token ??= (string) Str::uuid();
            }
        });

        static::saving(function (self $user): void {
            if (in_array($user->cent_role ?: $user->role, ['alumno'], true) && blank($user->cent_public_token)) {
                $user->cent_public_token = (string) Str::uuid();
            }
        });
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

    public function ordenesPrestacion(): HasMany
    {
        return $this->hasMany(OrdenPrestacion::class, 'afiliado_id');
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

    public function hasPermission(string $permission): bool
    {
        foreach ($this->permissionSet() as $grantedPermission) {
            if ($grantedPermission === '*' || $grantedPermission === $permission) {
                return true;
            }

            if (str_ends_with($grantedPermission, '*')) {
                $prefix = rtrim($grantedPermission, '*');

                if (str_starts_with($permission, $prefix)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    public function hasResourcePermission(string $resourceClass, string $panel, string $action = 'view'): bool
    {
        $permission = config("permissions.resources.{$panel}.{$resourceClass}");

        if (! $permission) {
            return $panel === 'cent'
                ? $this->hasAnyPermission(['*', 'cent.*'])
                : $this->hasAnyPermission(['*', 'admin.*']);
        }

        if ($action === 'delete' && ! $this->hasAnyPermission(['*', 'admin.*', 'cent.*'])) {
            if ($panel === 'admin' && ! $this->hasPermission('admin.settings.manage')) {
                return false;
            }

            if ($panel === 'cent' && ! $this->hasAnyPermission(['cent.config.manage', 'cent.administracion.manage'])) {
                return false;
            }
        }

        return $this->hasPermission($permission);
    }

    public function hasPagePermission(string $pageClass, string $panel): bool
    {
        $permission = config("permissions.pages.{$panel}.{$pageClass}");

        if ($permission) {
            return $this->hasPermission($permission);
        }

        return $panel === 'cent'
            ? $this->hasAnyPermission(['cent.*', 'cent.config.manage', 'cent.administracion.manage', 'cent.alumnos.manage', 'cent.aula.manage', 'cent.portal.use'])
            : $this->hasAnyPermission(['admin.*', 'admin.settings.manage', 'admin.institucion.manage', 'admin.editor.manage', 'admin.atencion.manage', 'admin.afiliacion.manage', 'admin.padron.manage']);
    }

    public function centRoleName(): ?string
    {
        return $this->cent_role ?: $this->role;
    }

    public function permissionSet(): array
    {
        $permissions = [];

        if ($this->role === 'admin') {
            $permissions[] = '*';
        }

        if ($this->puede_ver_todas_las_filiales) {
            $permissions[] = 'admin.padron.manage';
            $permissions[] = 'admin.atencion.manage';
        }

        if (filled($this->perfil_interno) && $this->perfil_interno !== 'ninguno') {
            $permissions = [
                ...$permissions,
                ...config('permissions.admin_profiles.'.$this->perfil_interno, []),
            ];
        }

        $centRole = $this->centRoleName();

        if (filled($centRole)) {
            $permissions = [
                ...$permissions,
                ...config('permissions.cent_roles.'.$centRole, []),
            ];
        }

        return array_values(array_unique(array_filter(Arr::flatten($permissions))));
    }

    public function isSecretariaGeneral(): bool
    {
        return $this->secretaria?->slug === 'secretaria-general';
    }

    public function shouldScopeAdminWorkflowToSecretaria(): bool
    {
        return $this->perfil_interno === 'secretaria'
            && filled($this->secretaria_id)
            && ! $this->isSecretariaGeneral()
            && ! $this->hasAnyPermission(['*', 'admin.*']);
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
