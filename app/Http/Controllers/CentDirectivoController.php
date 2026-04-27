<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\CentSede;
use App\Models\Comision;
use App\Models\Inscripcion;
use App\Models\InscripcionMesaCent;
use App\Models\MatriculaCent;
use App\Models\Materia;
use App\Models\MesaExamenCent;
use App\Models\Nota;
use App\Models\PreinscripcionCent;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CentDirectivoController extends Controller
{
    public function dashboard(): View
    {
        $this->autorizarDirectivo();
        $sedeScope = $this->sedeScopeId();

        return view('cent74.directivo.dashboard', [
            'preinscripcionesPendientes' => PreinscripcionCent::when($sedeScope, fn ($q) => $q->where('cent_sede_id', $sedeScope))->where('estado', 'pendiente')->count(),
            'alumnos' => User::where('role', 'alumno')->when($sedeScope, fn ($q) => $q->whereHas('matriculasCent', fn ($m) => $m->where('cent_sede_id', $sedeScope)))->count(),
            'docentes' => User::where('role', 'docente')->count(),
            'sedes' => CentSede::where('activa', true)->count(),
            'ultimasPreinscripciones' => PreinscripcionCent::with(['carrera', 'sede'])->when($sedeScope, fn ($q) => $q->where('cent_sede_id', $sedeScope))->latest()->limit(8)->get(),
            'matriculasPorSede' => MatriculaCent::selectRaw('cent_sede_id, count(*) as total')->with('sede')->when($sedeScope, fn ($q) => $q->where('cent_sede_id', $sedeScope))->groupBy('cent_sede_id')->get(),
            'carreras' => Carrera::where('active', true)->withCount('materias')->get(),
            'comisiones' => Comision::with(['materia.carrera', 'sede', 'docente'])->when($sedeScope, fn ($q) => $q->where('cent_sede_id', $sedeScope))->latest()->limit(8)->get(),
            'mesasPendientes' => MesaExamenCent::when($sedeScope, fn ($q) => $q->where('cent_sede_id', $sedeScope))->whereIn('acta_estado', ['abierta', 'cerrada'])->count(),
            'mesasFinalizadas' => MesaExamenCent::when($sedeScope, fn ($q) => $q->where('cent_sede_id', $sedeScope))->where('acta_estado', 'aprobada')->count(),
            'proximasMesas' => MesaExamenCent::with(['materia.carrera', 'sede', 'docente'])
                ->when($sedeScope, fn ($q) => $q->where('cent_sede_id', $sedeScope))
                ->whereDate('fecha', '>=', now()->toDateString())
                ->orderBy('fecha')
                ->limit(6)
                ->get(),
        ]);
    }

    public function alumnos(): View
    {
        $this->autorizarDirectivo();

        return view('cent74.directivo.alumnos', [
            'alumnos' => User::where(function ($query) {
                $query->where('role', 'alumno')->orWhere('cent_role', 'alumno');
            })
                ->when($this->sedeScopeId(), fn ($query, $sedeId) => $query->whereHas('matriculasCent', fn ($m) => $m->where('cent_sede_id', $sedeId)))
                ->with(['matriculasCent.carrera', 'matriculasCent.sede'])
                ->withCount(['inscripcionesAcademicas', 'notasAcademicas'])
                ->orderBy('name')
                ->get(),
            'sedes' => CentSede::where('activa', true)->orderBy('orden')->get(),
        ]);
    }

    public function docentes(): View
    {
        $this->autorizarDirectivo();

        return view('cent74.directivo.docentes', [
            'docentes' => User::where(function ($query) {
                $query->where('role', 'docente')->orWhere('cent_role', 'docente');
            })
                ->with(['comisionesDocente.materia.carrera', 'comisionesDocente.sede'])
                ->withCount('comisionesDocente')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function comisiones(): View
    {
        $this->autorizarDirectivo();

        return view('cent74.directivo.comisiones', [
            'comisiones' => Comision::with(['materia.carrera', 'sede', 'docente'])
                ->when($this->sedeScopeId(), fn ($query, $sedeId) => $query->where('cent_sede_id', $sedeId))
                ->withCount(['inscripciones', 'notas'])
                ->latest()
                ->get(),
        ]);
    }

    public function crearComision(): View
    {
        $this->autorizarDirectivo();

        return view('cent74.directivo.comision-form', $this->catalogosComision() + [
            'comision' => new Comision([
                'year_cycle' => now()->year,
                'acta_estado' => 'abierta',
            ]),
            'modo' => 'crear',
        ]);
    }

    public function guardarComision(Request $request): RedirectResponse
    {
        $this->autorizarDirectivo();

        $data = $this->validarComision($request);
        $this->autorizarSedeId($data['cent_sede_id'] ?? null);
        $data['acta_estado'] = 'abierta';

        $comision = Comision::create($data);

        return redirect()
            ->route('cent.directivo.comisiones.editar', $comision)
            ->with('status', 'Comisión creada correctamente. Ya podés inscribir alumnos.');
    }

    public function editarComision(Comision $comision): View
    {
        $this->autorizarDirectivo();
        $this->autorizarComisionEnScope($comision);

        $comision->load(['materia.carrera', 'sede', 'docente', 'inscripciones.alumno']);

        return view('cent74.directivo.comision-form', $this->catalogosComision($comision) + [
            'comision' => $comision,
            'modo' => 'editar',
        ]);
    }

    public function actualizarComision(Request $request, Comision $comision): RedirectResponse
    {
        $this->autorizarDirectivo();

        $this->autorizarComisionEnScope($comision);
        $data = $this->validarComision($request);
        $this->autorizarSedeId($data['cent_sede_id'] ?? null);

        $comision->update($data);

        return back()->with('status', 'Comisión actualizada correctamente.');
    }

    public function inscribirAlumno(Request $request, Comision $comision): RedirectResponse
    {
        $this->autorizarDirectivo();
        $this->autorizarComisionEnScope($comision);

        $data = $request->validate([
            'alumno_id' => ['required', 'exists:users,id'],
            'status' => ['required', 'in:pendiente,aprobada,rechazada'],
        ]);

        $comision->load('materia');
        $this->autorizarAlumnoEnScope(User::findOrFail($data['alumno_id']));

        $matricula = MatriculaCent::where('user_id', $data['alumno_id'])
            ->where('carrera_id', $comision->materia->carrera_id)
            ->whereIn('estado', ['inscripto', 'cursando', 'regular'])
            ->first();

        if (! $matricula) {
            return back()->withErrors([
                'alumno_id' => 'El alumno seleccionado no tiene matrícula activa para la carrera de esta comisión.',
            ]);
        }

        Inscripcion::updateOrCreate(
            [
                'alumno_id' => $data['alumno_id'],
                'comision_id' => $comision->id,
            ],
            ['status' => $data['status']]
        );

        return back()->with('status', 'Alumno inscripto correctamente en la comisión.');
    }

    public function constanciaAlumno(User $alumno): Response
    {
        $this->autorizarDirectivo();
        $this->autorizarAlumnoEnScope($alumno);

        $alumno->load(['matriculasCent.carrera', 'matriculasCent.sede']);
        $matricula = $alumno->matriculasCent->sortByDesc('created_at')->first();

        abort_unless($matricula, 404, 'El alumno no tiene matrícula activa.');

        return Pdf::loadView('cent74.pdf.constancia-alumno', [
            'alumno' => $alumno,
            'matricula' => $matricula,
        ])->download('constancia-cent-'.$alumno->id.'.pdf');
    }

    public function fichaAlumnoPdf(User $alumno): Response
    {
        $this->autorizarDirectivo();
        $this->autorizarAlumnoEnScope($alumno);

        $alumno->load(['matriculasCent.carrera.materias', 'matriculasCent.sede']);

        $notas = Nota::where('alumno_id', $alumno->id)
            ->with(['comision.materia.carrera', 'cargadaPor'])
            ->latest()
            ->get();

        $inscripcionesMesa = InscripcionMesaCent::where('alumno_id', $alumno->id)
            ->with(['mesa.materia.carrera', 'mesa.sede', 'mesa.docente', 'cargadoPor'])
            ->latest()
            ->get();

        $matricula = $alumno->matriculasCent->sortByDesc('created_at')->first();

        abort_unless($matricula, 404, 'El alumno no tiene matrícula activa.');

        return Pdf::loadView('cent74.pdf.ficha-academica-alumno', [
            'alumno' => $alumno,
            'matricula' => $matricula,
            'notas' => $notas,
            'inscripcionesMesa' => $inscripcionesMesa,
        ])->download('ficha-academica-cent-'.$alumno->id.'.pdf');
    }

    public function actas(): View
    {
        $this->autorizarDirectivo();

        $comisiones = Comision::with(['materia.carrera', 'sede', 'docente', 'cerradaPor', 'aprobadaPor'])
            ->when($this->sedeScopeId(), fn ($query, $sedeId) => $query->where('cent_sede_id', $sedeId))
            ->withCount(['inscripciones', 'notas'])
            ->latest()
            ->get();

        return view('cent74.directivo.actas', [
            'comisiones' => $comisiones,
            'resumen' => [
                'abiertas' => $comisiones->where('acta_estado', 'abierta')->count(),
                'cerradas' => $comisiones->where('acta_estado', 'cerrada')->count(),
                'aprobadas' => $comisiones->where('acta_estado', 'aprobada')->count(),
                'alumnos' => $comisiones->sum('inscripciones_count'),
            ],
        ]);
    }

    public function actasMesas(): View
    {
        $this->autorizarDirectivo();

        $mesas = MesaExamenCent::with([
            'materia.carrera',
            'sede',
            'docente',
            'cerradaPor',
            'aprobadaPor',
        ])
            ->when($this->sedeScopeId(), fn ($query, $sedeId) => $query->where('cent_sede_id', $sedeId))
            ->withCount('inscripciones')
            ->orderByDesc('fecha')
            ->get();

        return view('cent74.directivo.actas-mesas', [
            'mesas' => $mesas,
            'resumen' => [
                'abiertas' => $mesas->where('acta_estado', 'abierta')->count(),
                'cerradas' => $mesas->where('acta_estado', 'cerrada')->count(),
                'aprobadas' => $mesas->where('acta_estado', 'aprobada')->count(),
                'inscriptos' => $mesas->sum('inscripciones_count'),
            ],
        ]);
    }

    public function actaMesaPdf(MesaExamenCent $mesa): Response
    {
        $this->autorizarDirectivo();
        $this->autorizarMesaEnScope($mesa);

        $mesa->load([
            'materia.carrera',
            'sede',
            'docente',
            'cerradaPor',
            'aprobadaPor',
            'inscripciones.alumno',
            'inscripciones.cargadoPor',
        ]);

        return Pdf::loadView('cent74.pdf.acta-mesa-examen', [
            'mesa' => $mesa,
        ])->setPaper('a4', 'landscape')
            ->download('acta-mesa-cent-'.$mesa->id.'.pdf');
    }

    public function aprobarActaMesa(Request $request, MesaExamenCent $mesa): RedirectResponse
    {
        $this->autorizarDirectivo();
        $this->autorizarMesaEnScope($mesa);

        $data = $request->validate([
            'acta_libro' => ['nullable', 'string', 'max:80'],
            'acta_folio' => ['nullable', 'string', 'max:80'],
            'acta_observaciones' => ['nullable', 'string', 'max:2000'],
        ]);

        $mesa->update([
            'estado' => 'finalizada',
            'acta_estado' => 'aprobada',
            'acta_aprobada_at' => now(),
            'acta_aprobada_por' => auth()->id(),
            'acta_libro' => $data['acta_libro'] ?? $mesa->acta_libro,
            'acta_folio' => $data['acta_folio'] ?? $mesa->acta_folio,
            'acta_observaciones' => $data['acta_observaciones'] ?? $mesa->acta_observaciones,
        ]);

        return back()->with('status', 'Acta final de mesa aprobada correctamente.');
    }

    public function reabrirActaMesa(Request $request, MesaExamenCent $mesa): RedirectResponse
    {
        $this->autorizarDirectivo();
        $this->autorizarMesaEnScope($mesa);

        $data = $request->validate([
            'acta_observaciones' => ['nullable', 'string', 'max:2000'],
        ]);

        $mesa->update([
            'estado' => 'abierta',
            'acta_estado' => 'abierta',
            'acta_cerrada_at' => null,
            'acta_cerrada_por' => null,
            'acta_aprobada_at' => null,
            'acta_aprobada_por' => null,
            'acta_observaciones' => $data['acta_observaciones'] ?? $mesa->acta_observaciones,
        ]);

        return back()->with('status', 'Mesa reabierta para correcciones.');
    }

    public function reportes(): View
    {
        $this->autorizarDirectivo();

        return view('cent74.directivo.reportes', [
            'matriculasPorCarrera' => MatriculaCent::selectRaw('carrera_id, count(*) as total')
                ->with('carrera')
                ->groupBy('carrera_id')
                ->get(),
            'matriculasPorSede' => MatriculaCent::selectRaw('cent_sede_id, count(*) as total')
                ->with('sede')
                ->groupBy('cent_sede_id')
                ->get(),
            'preinscripcionesPorEstado' => PreinscripcionCent::selectRaw('estado, count(*) as total')
                ->groupBy('estado')
                ->get(),
            'notasPorEstado' => Nota::selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->get(),
            'inscripcionesPorEstado' => Inscripcion::selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->get(),
            'comisiones' => Comision::with(['materia.carrera', 'sede', 'docente'])->withCount('inscripciones')->latest()->get(),
        ]);
    }

    private function autorizarDirectivo(): void
    {
        $centRole = auth()->user()->cent_role ?: auth()->user()->role;
        abort_unless(in_array($centRole, ['admin', 'directivo', 'coordinador'], true), 403);
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

    private function autorizarComisionEnScope(Comision $comision): void
    {
        $this->autorizarSedeId($comision->cent_sede_id);
    }

    private function autorizarMesaEnScope(MesaExamenCent $mesa): void
    {
        $this->autorizarSedeId($mesa->cent_sede_id);
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

    private function validarComision(Request $request): array
    {
        return $request->validate([
            'materia_id' => ['required', 'exists:materias,id'],
            'cent_sede_id' => ['required', 'exists:cent_sedes,id'],
            'docente_id' => ['required', 'exists:users,id'],
            'year_cycle' => ['required', 'integer', 'min:2020', 'max:2100'],
            'schedule' => ['nullable', 'string', 'max:255'],
        ]);
    }

    private function catalogosComision(?Comision $comision = null): array
    {
        $alumnos = User::where(function ($query) {
            $query->where('role', 'alumno')->orWhere('cent_role', 'alumno');
        })
            ->with(['matriculasCent.carrera', 'matriculasCent.sede'])
            ->orderBy('name')
            ->get();

        return [
            'materias' => Materia::with('carrera')->orderBy('year')->orderBy('name')->get(),
            'sedes' => CentSede::where('activa', true)
                ->when($this->sedeScopeId(), fn ($query, $sedeId) => $query->whereKey($sedeId))
                ->orderBy('orden')
                ->orderBy('nombre')
                ->get(),
            'docentes' => User::where(function ($query) {
                $query->where('role', 'docente')->orWhere('cent_role', 'docente');
            })->orderBy('name')->get(),
            'alumnos' => $alumnos,
            'alumnosDisponibles' => $comision?->materia
                ? $alumnos->filter(fn ($alumno) => $alumno->matriculasCent->contains('carrera_id', $comision->materia->carrera_id))
                : $alumnos,
        ];
    }
}
