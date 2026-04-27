<?php

namespace App\Services\Cent;

use App\Models\MatriculaCent;
use App\Models\PreinscripcionCent;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MatricularPreinscripcion
{
    public function ejecutar(PreinscripcionCent $preinscripcion, string $password, string $estadoMatricula = 'cursando'): MatriculaCent
    {
        return DB::transaction(function () use ($preinscripcion, $password, $estadoMatricula): MatriculaCent {
            $preinscripcion->loadMissing(['carrera', 'sede']);

            $user = $this->resolverUsuario($preinscripcion, $password);

            $matricula = MatriculaCent::firstOrNew([
                'user_id' => $user->id,
                'carrera_id' => $preinscripcion->carrera_id,
                'ciclo_lectivo' => $preinscripcion->ciclo_lectivo,
            ]);

            $matricula->fill([
                'cent_sede_id' => $preinscripcion->cent_sede_id,
                'legajo' => $matricula->legajo ?: $this->generarLegajo($preinscripcion),
                'estado' => $estadoMatricula,
                'fecha_ingreso' => $matricula->fecha_ingreso ?: now()->toDateString(),
                'observaciones' => trim((string) $preinscripcion->observaciones_admin)
                    ?: ($matricula->observaciones ?: 'Matrícula generada desde preinscripción '.$preinscripcion->codigo),
            ]);

            $matricula->save();

            $preinscripcion->update([
                'user_id' => $user->id,
                'estado' => 'inscripta',
                'aprobado_por' => auth()->id(),
                'aprobado_at' => now(),
            ]);

            return $matricula;
        });
    }

    private function resolverUsuario(PreinscripcionCent $preinscripcion, string $password): User
    {
        $userByDni = User::where('dni', $preinscripcion->dni)->first();
        $userByEmail = User::where('email', $preinscripcion->email)->first();

        if ($userByDni && $userByEmail && $userByDni->id !== $userByEmail->id) {
            throw ValidationException::withMessages([
                'email' => 'El DNI y el email pertenecen a usuarios distintos. Revisá la preinscripción antes de matricular.',
            ]);
        }

        $user = $userByDni ?: $userByEmail;

        if (! $user) {
            return User::create([
                'name' => $preinscripcion->apellido_nombre,
                'email' => $preinscripcion->email,
                'password' => $password,
                'role' => 'alumno',
                'cent_role' => 'alumno',
                'dni' => $preinscripcion->dni,
                'phone' => $preinscripcion->telefono,
                'address' => $preinscripcion->domicilio,
                'active' => true,
            ]);
        }

        $user->forceFill([
            'name' => $user->name ?: $preinscripcion->apellido_nombre,
            'dni' => $user->dni ?: $preinscripcion->dni,
            'phone' => $user->phone ?: $preinscripcion->telefono,
            'address' => $user->address ?: $preinscripcion->domicilio,
            'cent_role' => 'alumno',
            'active' => true,
        ]);

        if (filled($password)) {
            $user->password = $password;
        }

        $user->save();

        return $user;
    }

    private function generarLegajo(PreinscripcionCent $preinscripcion): string
    {
        $base = 'CENT'.$preinscripcion->ciclo_lectivo.'-';
        $ultimo = MatriculaCent::where('legajo', 'like', $base.'%')->max('legajo');
        $numero = $ultimo ? ((int) substr($ultimo, -5)) + 1 : 1;

        do {
            $legajo = $base.str_pad((string) $numero, 5, '0', STR_PAD_LEFT);
            $numero++;
        } while (MatriculaCent::where('legajo', $legajo)->exists());

        return $legajo;
    }
}
