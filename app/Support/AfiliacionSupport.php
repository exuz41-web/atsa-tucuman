<?php

namespace App\Support;

use App\Models\Filial;
use App\Models\SolicitudAfiliacion;
use App\Models\User;
use App\Notifications\AfiliacionAprobadaNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RuntimeException;

class AfiliacionSupport
{
    public static function nextNumeroAfiliado(): string
    {
        $next = ((int) User::max('id')) + 1;
        $prefix = 'ATSA'.now()->year.'-';

        do {
            $numero = $prefix.str_pad((string) $next, 5, '0', STR_PAD_LEFT);
            $next++;
        } while (User::where('numero_afiliado', $numero)->exists());

        return $numero;
    }

    public static function aprobarSolicitud(SolicitudAfiliacion $solicitud, ?int $reviewedBy = null): array
    {
        if ($solicitud->user_id) {
            $user = User::find($solicitud->user_id);

            if ($user) {
                return [
                    'user' => $user,
                    'password' => null,
                    'created' => false,
                ];
            }
        }

        $dni = preg_replace('/\D+/', '', (string) $solicitud->numero_documento);

        if (User::where('dni', $dni)->exists()) {
            throw new RuntimeException('Ya existe un usuario con este DNI. Revisa la solicitud antes de aprobarla.');
        }

        if (filled($solicitud->email) && User::where('email', $solicitud->email)->exists()) {
            throw new RuntimeException('Ya existe un usuario con este email. Revisa la solicitud antes de aprobarla.');
        }

        $password = Str::password(10);

        return DB::transaction(function () use ($solicitud, $reviewedBy, $dni, $password): array {
            $numeroAfiliado = self::nextNumeroAfiliado();

            $user = User::create([
                'name' => $solicitud->apellido_nombre,
                'dni' => $dni,
                'email' => $solicitud->email,
                'phone' => $solicitud->telefono,
                'address' => $solicitud->domicilio,
                'filial_id' => self::resolveFilialId($solicitud->filial_preferida),
                'password' => $password,
                'role' => 'afiliado',
                'numero_afiliado' => $numeroAfiliado,
                'active' => true,
                'estado_afiliado' => 'activo',
                'fecha_alta' => now()->toDateString(),
                'lugar_trabajo' => $solicitud->establecimiento,
                'categoria_laboral' => $solicitud->profesion,
                'carnet_activo' => true,
                'carnet_vencimiento' => now()->endOfYear()->toDateString(),
                'carnet_emitido_at' => now(),
            ]);

            $solicitud->update([
                'estado' => 'aprobada',
                'reviewed_by' => $reviewedBy,
                'reviewed_at' => now(),
                'user_id' => $user->id,
                'numero_afiliado_generado' => $numeroAfiliado,
            ]);

            $resetUrl = self::createPasswordActivationUrl($user);
            $user->notify(new AfiliacionAprobadaNotification($user, $resetUrl));

            return [
                'user' => $user,
                'password' => $password,
                'reset_url' => $resetUrl,
                'created' => true,
            ];
        });
    }

    public static function resolveFilialId(?string $filialPreferida): ?int
    {
        if (blank($filialPreferida)) {
            return null;
        }

        $normalized = Str::of($filialPreferida)->lower()->ascii()->value();

        return match (true) {
            str_contains($normalized, 'concepcion'),
            str_contains($normalized, 'sur') => Filial::where('slug', 'filial-del-sur')->value('id'),
            str_contains($normalized, 'banda'),
            str_contains($normalized, 'este') => Filial::where('slug', 'filial-este')->value('id'),
            str_contains($normalized, 'capital'),
            str_contains($normalized, 'central'),
            str_contains($normalized, 'ciudad deportiva'),
            str_contains($normalized, 'paraguay') => Filial::where('slug', 'central-ciudad-deportiva')->value('id'),
            default => Filial::query()
                ->whereRaw('LOWER(name) like ?', ['%'.$normalized.'%'])
                ->orWhereRaw('LOWER(address) like ?', ['%'.$normalized.'%'])
                ->value('id'),
        };
    }

    public static function createPasswordActivationUrl(User $user): string
    {
        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        return route('afiliados.password.reset', [
            'token' => $token,
            'email' => $user->email,
        ]);
    }
}
