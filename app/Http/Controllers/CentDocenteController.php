<?php

namespace App\Http\Controllers;

use App\Models\AsistenciaCent;
use App\Models\Comision;
use App\Models\InscripcionMesaCent;
use App\Models\MatriculaCent;
use App\Models\Nota;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CentDocenteController extends Controller
{
    private array $tiposNota = ['parcial1', 'parcial2', 'recuperatorio', 'final'];
    private array $estadosNota = ['aprobado', 'desaprobado', 'ausente', 'libre'];
    private array $estadosAsistencia = ['presente', 'ausente', 'tarde', 'justificado'];

    public function dashboard(): View
    {
        $docente = auth()->user();
        $centRole = $docente->cent_role ?: $docente->role;
        abort_unless(in_array($centRole, ['docente', 'admin', 'directivo', 'coordinador'], true), 403);

        return view('cent74.docente.dashboard', [
            'docente' => $docente,
            'comisiones' => $this->comisionesDelDocente($docente),
            'avisos' => $this->avisosDocente(),
        ]);
    }

    public function comisiones(): View
    {
        $docente = auth()->user();
        $centRole = $docente->cent_role ?: $docente->role;
        abort_unless(in_array($centRole, ['docente', 'admin', 'directivo', 'coordinador'], true), 403);

        return view('cent74.docente.comisiones', [
            'docente' => $docente,
            'comisiones' => $this->comisionesDelDocente($docente),
        ]);
    }

    public function asistencia(Comision $comision): View
    {
        $this->autorizarComision($comision);

        $fecha = request('fecha') ?: now()->toDateString();

        $comision->load([
            'materia.carrera',
            'sede',
            'docente',
            'inscripciones.alumno',
            'asistencias' => fn ($query) => $query->whereDate('fecha', $fecha),
        ]);

        $asistencias = $comision->asistencias->keyBy('alumno_id');

        return view('cent74.docente.asistencia', [
            'comision' => $comision,
            'fecha' => $fecha,
            'asistencias' => $asistencias,
            'estadosAsistencia' => $this->estadosAsistencia,
        ]);
    }

    public function guardarAsistencia(Request $request, Comision $comision): RedirectResponse
    {
        $this->autorizarComision($comision);

        $data = $request->validate([
            'fecha' => ['required', 'date'],
            'asistencias' => ['nullable', 'array'],
            'asistencias.*.estado' => ['required', 'in:presente,ausente,tarde,justificado'],
            'asistencias.*.observaciones' => ['nullable', 'string', 'max:500'],
        ]);

        $alumnosValidos = $comision->inscripciones()->pluck('alumno_id')->map(fn ($id) => (int) $id)->all();
        $guardadas = 0;

        foreach (($data['asistencias'] ?? []) as $alumnoId => $asistenciaData) {
            if (! in_array((int) $alumnoId, $alumnosValidos, true)) {
                continue;
            }

            AsistenciaCent::updateOrCreate(
                [
                    'comision_id' => $comision->id,
                    'alumno_id' => (int) $alumnoId,
                    'fecha' => $data['fecha'],
                ],
                [
                    'estado' => $asistenciaData['estado'],
                    'observaciones' => $asistenciaData['observaciones'] ?? null,
                    'cargado_por' => auth()->id(),
                ]
            );

            $guardadas++;
        }

        return redirect()
            ->route('cent.docente.asistencia', ['comision' => $comision, 'fecha' => $data['fecha']])
            ->with('status', 'Asistencia guardada correctamente. Registros actualizados: '.$guardadas.'.');
    }

    public function fichaAlumnoPdf(User $alumno): Response
    {
        $centRole = auth()->user()->cent_role ?: auth()->user()->role;
        $puedeVer = in_array($centRole, ['admin', 'directivo', 'coordinador'], true)
            || Comision::where('docente_id', auth()->id())
                ->whereHas('inscripciones', fn ($query) => $query->where('alumno_id', $alumno->id))
                ->exists();

        abort_unless($puedeVer, 403);
        $this->autorizarAlumnoEnScope($alumno);

        $matricula = MatriculaCent::where('user_id', $alumno->id)
            ->with(['carrera', 'sede'])
            ->latest()
            ->first();

        abort_unless($matricula, 404, 'El alumno no tiene matrícula activa.');

        $notas = Nota::where('alumno_id', $alumno->id)
            ->with(['comision.materia.carrera', 'cargadaPor'])
            ->latest()
            ->get();

        $inscripcionesMesa = InscripcionMesaCent::where('alumno_id', $alumno->id)
            ->with(['mesa.materia.carrera', 'mesa.sede', 'mesa.docente', 'cargadoPor'])
            ->latest()
            ->get();

        return Pdf::loadView('cent74.pdf.ficha-academica-alumno', [
            'alumno' => $alumno,
            'matricula' => $matricula,
            'notas' => $notas,
            'inscripcionesMesa' => $inscripcionesMesa,
        ])->download('ficha-academica-cent-'.$alumno->id.'.pdf');
    }

    public function planilla(Comision $comision): View
    {
        $this->autorizarComision($comision);

        [$comision, $notas] = $this->datosPlanilla($comision);

        return view('cent74.docente.planilla', [
            'comision' => $comision,
            'notas' => $notas,
            'tiposNota' => $this->tiposNota,
            'estadosNota' => $this->estadosNota,
        ]);
    }

    public function descargarActa(Comision $comision): Response
    {
        $this->autorizarComision($comision);

        [$comision, $notas] = $this->datosPlanilla($comision);

        $pdf = Pdf::loadView('cent74.pdf.acta-notas', [
            'comision' => $comision,
            'notas' => $notas,
            'tiposNota' => $this->tiposNota,
        ])->setPaper('a4', 'landscape');

        $filename = 'acta-cent-'.$comision->id.'-'.$comision->year_cycle.'.pdf';

        return $pdf->download($filename);
    }

    public function guardarPlanilla(Request $request, Comision $comision): RedirectResponse
    {
        $this->autorizarComision($comision);
        $this->asegurarActaEditable($comision);

        $data = $request->validate([
            'notas' => ['nullable', 'array'],
            'notas.*' => ['array'],
            'notas.*.*.grade' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'notas.*.*.status' => ['nullable', 'in:aprobado,desaprobado,ausente,libre'],
        ]);

        $alumnosValidos = $comision->inscripciones()->pluck('alumno_id')->map(fn ($id) => (int) $id)->all();
        $guardadas = 0;

        foreach (($data['notas'] ?? []) as $alumnoId => $notasAlumno) {
            if (! in_array((int) $alumnoId, $alumnosValidos, true)) {
                continue;
            }

            foreach ($notasAlumno as $type => $notaData) {
                if (! in_array($type, $this->tiposNota, true)) {
                    continue;
                }

                $grade = $notaData['grade'] ?? null;
                $status = $notaData['status'] ?? null;

                if ($grade === null && blank($status)) {
                    continue;
                }

                Nota::updateOrCreate(
                    [
                        'alumno_id' => (int) $alumnoId,
                        'comision_id' => $comision->id,
                        'type' => $type,
                    ],
                    [
                        'grade' => $grade === null || $grade === '' ? null : $grade,
                        'status' => $status ?: $this->resolverEstadoPorNota($grade),
                        'loaded_by' => auth()->id(),
                    ]
                );

                $guardadas++;
            }
        }

        return redirect()
            ->route('cent.docente.planilla', $comision)
            ->with('status', 'Planilla guardada correctamente. Registros actualizados: '.$guardadas.'.');
    }

    public function cargarNota(Request $request, Comision $comision): RedirectResponse
    {
        $centRole = auth()->user()->cent_role ?: auth()->user()->role;
        abort_unless($comision->docente_id === auth()->id() || in_array($centRole, ['admin', 'directivo', 'coordinador'], true), 403);
        $this->asegurarActaEditable($comision);

        $data = $request->validate([
            'alumno_id' => ['required', 'exists:users,id'],
            'type' => ['required', 'in:parcial1,parcial2,final,recuperatorio'],
            'grade' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'status' => ['required', 'in:aprobado,desaprobado,ausente,libre'],
        ]);

        $data['comision_id'] = $comision->id;
        $data['loaded_by'] = auth()->id();

        Nota::updateOrCreate(
            ['alumno_id' => $data['alumno_id'], 'comision_id' => $comision->id, 'type' => $data['type']],
            $data
        );

        return back()->with('status', 'Nota cargada correctamente.');
    }

    public function cerrarActa(Request $request, Comision $comision): RedirectResponse
    {
        $this->autorizarComision($comision);
        $this->asegurarActaEditable($comision);

        $data = $request->validate([
            'acta_observaciones' => ['nullable', 'string', 'max:2000'],
        ]);

        $comision->loadCount('inscripciones');

        if ($comision->inscripciones_count < 1) {
            return back()->withErrors(['acta' => 'No se puede cerrar un acta sin alumnos inscriptos.']);
        }

        $comision->update([
            'acta_estado' => 'cerrada',
            'acta_cerrada_at' => now(),
            'acta_cerrada_por' => auth()->id(),
            'acta_aprobada_at' => null,
            'acta_aprobada_por' => null,
            'acta_observaciones' => $data['acta_observaciones'] ?? $comision->acta_observaciones,
        ]);

        return back()->with('status', 'Acta cerrada correctamente. Direccion ya puede revisarla.');
    }

    public function aprobarActa(Request $request, Comision $comision): RedirectResponse
    {
        $this->autorizarDirectivo();

        $data = $request->validate([
            'acta_observaciones' => ['nullable', 'string', 'max:2000'],
        ]);

        $comision->update([
            'acta_estado' => 'aprobada',
            'acta_aprobada_at' => now(),
            'acta_aprobada_por' => auth()->id(),
            'acta_observaciones' => $data['acta_observaciones'] ?? $comision->acta_observaciones,
        ]);

        return back()->with('status', 'Acta aprobada correctamente.');
    }

    public function reabrirActa(Request $request, Comision $comision): RedirectResponse
    {
        $this->autorizarDirectivo();

        $data = $request->validate([
            'acta_observaciones' => ['nullable', 'string', 'max:2000'],
        ]);

        $comision->update([
            'acta_estado' => 'abierta',
            'acta_cerrada_at' => null,
            'acta_cerrada_por' => null,
            'acta_aprobada_at' => null,
            'acta_aprobada_por' => null,
            'acta_observaciones' => $data['acta_observaciones'] ?? $comision->acta_observaciones,
        ]);

        return back()->with('status', 'Acta reabierta para correcciones.');
    }

    private function autorizarComision(Comision $comision): void
    {
        $centRole = auth()->user()->cent_role ?: auth()->user()->role;

        abort_unless(
            $comision->docente_id === auth()->id() || in_array($centRole, ['admin', 'directivo', 'coordinador'], true),
            403
        );

        $this->autorizarSedeId($comision->cent_sede_id);
    }

    private function autorizarDirectivo(): void
    {
        $centRole = auth()->user()->cent_role ?: auth()->user()->role;

        abort_unless(in_array($centRole, ['admin', 'directivo', 'coordinador'], true), 403);
    }

    private function asegurarActaEditable(Comision $comision): void
    {
        if (in_array($comision->acta_estado, ['cerrada', 'aprobada'], true)) {
            abort(403, 'El acta esta cerrada o aprobada. Debe reabrirse antes de editar notas.');
        }
    }

    private function resolverEstadoPorNota(mixed $grade): string
    {
        if ($grade === null || $grade === '') {
            return 'ausente';
        }

        return ((float) $grade) >= 6 ? 'aprobado' : 'desaprobado';
    }

    private function autorizarAlumnoEnScope(User $alumno): void
    {
        $sedeScope = $this->sedeScopeId();

        if (! $sedeScope) {
            return;
        }

        abort_unless(
            $alumno->matriculasCent()->where('cent_sede_id', $sedeScope)->exists(),
            403
        );
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

    private function datosPlanilla(Comision $comision): array
    {
        $comision->load([
            'materia.carrera',
            'sede',
            'docente',
            'cerradaPor',
            'aprobadaPor',
            'inscripciones.alumno',
            'notas',
            'asistencias',
        ]);

        $notas = $comision->notas
            ->groupBy('alumno_id')
            ->map(fn ($items) => $items->keyBy('type'));

        return [$comision, $notas];
    }

    private function comisionesDelDocente($docente)
    {
        $centRole = $docente->cent_role ?: $docente->role;

        return Comision::query()
            ->when(! in_array($centRole, ['admin', 'directivo', 'coordinador'], true), fn ($query) => $query->where('docente_id', $docente->id))
            ->with(['materia.carrera', 'sede', 'inscripciones.alumno', 'notas', 'asistencias'])
            ->latest()
            ->get();
    }

    private function avisosDocente()
    {
        return \App\Models\AvisoCent::vigentes()
            ->whereIn('rol_destino', ['todos', 'docente'])
            ->with(['carrera', 'sede'])
            ->orderByDesc('destacado')
            ->latest()
            ->limit(5)
            ->get();
    }
}
