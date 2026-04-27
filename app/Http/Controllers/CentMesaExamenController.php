<?php

namespace App\Http\Controllers;

use App\Models\InscripcionMesaCent;
use App\Models\MatriculaCent;
use App\Models\MesaExamenCent;
use App\Services\Cent\CorrelatividadService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CentMesaExamenController extends Controller
{
    public function __construct(private CorrelatividadService $correlatividades) {}

    public function alumnoIndex(): View
    {
        $alumno = auth()->user();
        $this->autorizarRol(['alumno', 'admin', 'directivo', 'coordinador']);

        $matriculas = $alumno->matriculasCent()->with(['carrera', 'sede'])->get();
        $carreraIds = $matriculas->pluck('carrera_id')->filter()->unique();
        $sedeIds = $matriculas->pluck('cent_sede_id')->filter()->unique();

        $mesas = MesaExamenCent::query()
            ->with(['materia.carrera', 'sede', 'docente', 'inscripciones'])
            ->where('estado', 'abierta')
            ->whereDate('fecha', '>=', now()->toDateString())
            ->whereHas('materia', fn ($query) => $query->whereIn('carrera_id', $carreraIds))
            ->where(fn ($query) => $query->whereNull('cent_sede_id')->orWhereIn('cent_sede_id', $sedeIds))
            ->orderBy('fecha')
            ->get();

        $inscripciones = InscripcionMesaCent::where('alumno_id', $alumno->id)
            ->with(['mesa.materia.carrera', 'mesa.sede', 'mesa.docente'])
            ->latest()
            ->get();

        return view('cent74.alumno.mesas', [
            'alumno' => $alumno,
            'mesas' => $mesas,
            'inscripcionesMesa' => $inscripciones,
        ]);
    }

    public function inscribirAlumno(MesaExamenCent $mesa): RedirectResponse
    {
        $alumno = auth()->user();
        $this->autorizarRol(['alumno', 'admin', 'directivo', 'coordinador']);

        $mesa->load('materia');

        if ($mesa->estado !== 'abierta' || $mesa->fecha->lt(now()->startOfDay())) {
            return back()->withErrors(['mesa' => 'La mesa no está disponible para inscripción.']);
        }

        $matricula = MatriculaCent::where('user_id', $alumno->id)
            ->where('carrera_id', $mesa->materia->carrera_id)
            ->whereIn('estado', ['inscripto', 'cursando', 'regular'])
            ->first();

        if (! $matricula) {
            return back()->withErrors(['mesa' => 'No tenés matrícula activa para la carrera de esta mesa.']);
        }

        if (! $mesa->tiene_cupo) {
            return back()->withErrors(['mesa' => 'La mesa ya no tiene cupo disponible.']);
        }

        $faltantes = $this->correlatividades->faltantes($alumno, $mesa->materia);
        if ($faltantes !== []) {
            return back()->withErrors([
                'mesa' => 'No cumplís las correlatividades requeridas para rendir esta materia.',
            ]);
        }

        InscripcionMesaCent::updateOrCreate(
            [
                'mesa_examen_cent_id' => $mesa->id,
                'alumno_id' => $alumno->id,
            ],
            ['estado' => 'inscripto']
        );

        return back()->with('status', 'Inscripción a mesa registrada correctamente.');
    }

    public function comprobante(InscripcionMesaCent $inscripcion): Response
    {
        $this->autorizarInscripcion($inscripcion);

        $inscripcion->load(['alumno', 'mesa.materia.carrera', 'mesa.sede', 'mesa.docente']);

        return Pdf::loadView('cent74.pdf.comprobante-mesa', [
            'inscripcion' => $inscripcion,
        ])->download('comprobante-mesa-cent-'.$inscripcion->id.'.pdf');
    }

    public function docenteIndex(): View
    {
        $user = auth()->user();
        $centRole = $user->cent_role ?: $user->role;
        $this->autorizarRol(['docente', 'admin', 'directivo', 'coordinador']);

        $mesas = MesaExamenCent::query()
            ->when(! in_array($centRole, ['admin', 'directivo', 'coordinador'], true), fn ($query) => $query->where('docente_id', $user->id))
            ->with(['materia.carrera', 'sede', 'docente'])
            ->withCount('inscripciones')
            ->orderByDesc('fecha')
            ->get();

        return view('cent74.docente.mesas', [
            'mesas' => $mesas,
        ]);
    }

    public function docenteShow(MesaExamenCent $mesa): View
    {
        $this->autorizarMesaDocente($mesa);

        $mesa->load(['materia.carrera', 'sede', 'docente', 'inscripciones.alumno']);

        return view('cent74.docente.mesa-show', [
            'mesa' => $mesa,
        ]);
    }

    public function guardarResultados(Request $request, MesaExamenCent $mesa): RedirectResponse
    {
        $this->autorizarMesaDocente($mesa);

        $data = $request->validate([
            'resultados' => ['nullable', 'array'],
            'resultados.*.estado' => ['required', 'in:inscripto,cancelado,presente,ausente,aprobado,desaprobado'],
            'resultados.*.nota' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'resultados.*.observaciones' => ['nullable', 'string', 'max:1000'],
            'cerrar_mesa' => ['nullable', 'boolean'],
            'acta_observaciones' => ['nullable', 'string', 'max:2000'],
        ]);

        $inscripciones = $mesa->inscripciones()->pluck('id')->map(fn ($id) => (int) $id)->all();
        $actualizadas = 0;

        foreach (($data['resultados'] ?? []) as $inscripcionId => $resultado) {
            if (! in_array((int) $inscripcionId, $inscripciones, true)) {
                continue;
            }

            InscripcionMesaCent::whereKey($inscripcionId)->update([
                'estado' => $resultado['estado'],
                'nota' => $resultado['nota'] ?? null,
                'observaciones' => $resultado['observaciones'] ?? null,
                'cargado_por' => auth()->id(),
            ]);

            $actualizadas++;
        }

        if ($request->boolean('cerrar_mesa')) {
            $mesa->update([
                'estado' => 'finalizada',
                'acta_estado' => 'cerrada',
                'acta_cerrada_at' => now(),
                'acta_cerrada_por' => auth()->id(),
                'acta_aprobada_at' => null,
                'acta_aprobada_por' => null,
                'acta_observaciones' => $data['acta_observaciones'] ?? $mesa->acta_observaciones,
            ]);
        }

        return back()->with('status', 'Resultados guardados correctamente. Registros actualizados: '.$actualizadas.'.');
    }

    private function autorizarRol(array $roles): void
    {
        $centRole = auth()->user()->cent_role ?: auth()->user()->role;
        abort_unless(in_array($centRole, $roles, true), 403);
    }

    private function autorizarInscripcion(InscripcionMesaCent $inscripcion): void
    {
        $centRole = auth()->user()->cent_role ?: auth()->user()->role;
        abort_unless(
            $inscripcion->alumno_id === auth()->id() || in_array($centRole, ['admin', 'directivo', 'coordinador'], true),
            403
        );

        $inscripcion->loadMissing('mesa');
        $this->autorizarSedeId($inscripcion->mesa?->cent_sede_id);
    }

    private function autorizarMesaDocente(MesaExamenCent $mesa): void
    {
        $centRole = auth()->user()->cent_role ?: auth()->user()->role;
        abort_unless(
            $mesa->docente_id === auth()->id() || in_array($centRole, ['admin', 'directivo', 'coordinador'], true),
            403
        );

        $this->autorizarSedeId($mesa->cent_sede_id);
    }

    private function autorizarSedeId(?int $sedeId): void
    {
        $sedeScope = $this->sedeScopeId();

        if (! $sedeScope) {
            return;
        }

        abort_unless((int) $sedeId === $sedeScope, 403);
    }

    private function sedeScopeId(): ?int
    {
        $user = auth()->user();
        $centRole = $user->cent_role ?: $user->role;

        if (in_array($centRole, ['coordinador', 'directivo'], true) && $user->cent_sede_id) {
            return (int) $user->cent_sede_id;
        }

        return null;
    }
}
