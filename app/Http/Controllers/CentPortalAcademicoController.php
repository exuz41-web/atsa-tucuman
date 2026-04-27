<?php

namespace App\Http\Controllers;

use App\Models\CentCuota;
use App\Models\CentEvento;
use App\Models\CentLegajoDocumento;
use App\Models\CentNotificacion;
use App\Services\Cent\CentNotificar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CentPortalAcademicoController extends Controller
{
    public function calendario(): View
    {
        $user = auth()->user();
        $role = $user->cent_role ?: $user->role;
        $matriculas = $user->matriculasCent()->get(['carrera_id', 'cent_sede_id']);

        $eventos = CentEvento::where('activo', true)
            ->whereIn('rol_destino', ['todos', $role])
            ->where(fn ($query) => $query->whereNull('carrera_id')->orWhereIn('carrera_id', $matriculas->pluck('carrera_id')->filter()))
            ->where(fn ($query) => $query->whereNull('cent_sede_id')->orWhereIn('cent_sede_id', $matriculas->pluck('cent_sede_id')->filter()))
            ->with(['carrera', 'sede'])
            ->orderBy('fecha_inicio')
            ->get();

        return view('cent74.calendario', compact('eventos'));
    }

    public function legajo(): View
    {
        $alumno = auth()->user();
        $documentos = $alumno->legajoCent()->latest()->get();

        return view('cent74.alumno.legajo', [
            'alumno' => $alumno,
            'documentos' => $documentos,
            'tipos' => CentLegajoDocumento::tipos(),
        ]);
    }

    public function subirDocumento(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tipo' => ['required', 'in:'.implode(',', array_keys(CentLegajoDocumento::tipos()))],
            'archivo' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'observaciones' => ['nullable', 'string', 'max:1000'],
        ]);

        $path = $request->file('archivo')->store('cent/legajos/'.auth()->id(), 'local');

        CentLegajoDocumento::updateOrCreate(
            ['user_id' => auth()->id(), 'tipo' => $data['tipo']],
            [
                'archivo' => $path,
                'estado' => 'pendiente',
                'observaciones' => $data['observaciones'] ?? null,
                'validado_por' => null,
                'validado_at' => null,
            ]
        );

        \App\Models\CentActivityLog::registrar('subió documento de legajo', null, CentLegajoDocumento::tipos()[$data['tipo']]);
        CentNotificar::usuario(auth()->id(), 'Documento recibido', 'Tu documento de legajo quedó pendiente de revisión.', 'legajo', route('cent.alumno.legajo'));

        return back()->with('status', 'Documento enviado correctamente. Quedó pendiente de validación.');
    }

    public function cuotas(): View
    {
        CentCuota::where('alumno_id', auth()->id())
            ->where('estado', 'pendiente')
            ->whereNotNull('vencimiento')
            ->whereDate('vencimiento', '<', now()->toDateString())
            ->update(['estado' => 'vencida']);

        $cuotas = CentCuota::where('alumno_id', auth()->id())
            ->with(['matricula.carrera', 'afiliadoDescuento', 'recibo'])
            ->orderByDesc('vencimiento')
            ->get();

        $deudaTotal = $cuotas->whereIn('estado', ['pendiente', 'vencida'])->sum('monto_final');

        return view('cent74.alumno.cuotas', compact('cuotas', 'deudaTotal'));
    }

    public function subirComprobante(Request $request, CentCuota $cuota): RedirectResponse
    {
        abort_unless($cuota->alumno_id === auth()->id(), 403);

        $request->validate([
            'comprobante' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
        ]);

        if ($cuota->comprobante) {
            $this->sensitiveDiskFor($cuota->comprobante)->delete($cuota->comprobante);
        }

        $cuota->update([
            'comprobante' => $request->file('comprobante')->store('cent/cuotas/'.auth()->id(), 'local'),
            'estado' => 'pendiente',
        ]);

        \App\Models\CentActivityLog::registrar('subió comprobante de cuota', $cuota, $cuota->concepto);
        CentNotificar::usuario(auth()->id(), 'Comprobante enviado', 'Administración revisará el comprobante de '.$cuota->concepto.'.', 'cuota', route('cent.alumno.cuotas'));

        return back()->with('status', 'Comprobante enviado. Administración revisará el pago.');
    }

    public function perfil(): View
    {
        return view('cent74.perfil', ['user' => auth()->user()]);
    }

    public function actualizarPerfil(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.auth()->id()],
            'phone' => ['nullable', 'string', 'max:80'],
            'address' => ['nullable', 'string', 'max:255'],
            'foto_perfil' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('foto_perfil')) {
            $data['foto_perfil'] = $request->file('foto_perfil')->store('cent/perfiles/'.auth()->id(), 'public');
        }

        auth()->user()->update($data);
        \App\Models\CentActivityLog::registrar('actualizó perfil académico');

        return back()->with('status', 'Perfil actualizado correctamente.');
    }

    public function notificaciones(): View
    {
        $user = auth()->user();
        $matriculas = $user->matriculasCent()->get(['cent_sede_id']);

        $notificaciones = CentNotificacion::query()
            ->where(function ($query) use ($user, $matriculas) {
                $query->where('user_id', $user->id)
                    ->orWhere(function ($query) use ($matriculas) {
                        $query->whereNull('user_id')
                            ->whereIn('cent_sede_id', $matriculas->pluck('cent_sede_id')->filter());
                    });
            })
            ->latest()
            ->paginate(12);

        return view('cent74.notificaciones', compact('notificaciones'));
    }

    public function leerNotificacion(CentNotificacion $notificacion): RedirectResponse
    {
        $user = auth()->user();
        $puedeLeer = $notificacion->user_id === $user->id
            || (
                blank($notificacion->user_id)
                && $notificacion->cent_sede_id
                && $user->matriculasCent()->where('cent_sede_id', $notificacion->cent_sede_id)->exists()
            );

        abort_unless($puedeLeer, 403);

        if (! $notificacion->leida_at) {
            $notificacion->update(['leida_at' => now()]);
        }

        return $notificacion->url
            ? redirect($notificacion->url)
            : redirect()->route('cent.notificaciones');
    }

    private function sensitiveDiskFor(string $path): FilesystemAdapter
    {
        if (Storage::disk('local')->exists($path)) {
            return Storage::disk('local');
        }

        return Storage::disk('public');
    }
}
