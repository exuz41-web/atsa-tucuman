@extends('layouts.cent')

@section('title', 'Permisos de examen')
@section('header', 'Permisos de examen')

@section('content')
<section class="portal-hero p-4 p-xl-5 mb-4">
    <span class="portal-kicker"><i class="ti ti-qr-code"></i> Permisos con QR</span>
    <h1 class="display-6 fw-bolder mt-3 mb-3">Permisos de examen</h1>
    <p class="fs-5 text-muted mb-0">Solicitá el permiso para rendir y descargalo cuando administración lo habilite.</p>
</section>

<div class="row g-4">
    <div class="col-xl-5">
        <div class="modern-card p-4 h-100">
            <h3 class="fw-bolder mb-4">Mesas inscriptas</h3>
            <div class="vstack gap-3">
                @forelse($inscripciones as $inscripcion)
                    <div class="soft-panel p-3">
                        <div class="d-flex gap-3">
                            <span class="stat-icon bg-primary-subtle text-primary"><i class="ti ti-calendar-event"></i></span>
                            <div class="flex-grow-1">
                                <strong>{{ $inscripcion->mesa->materia->name ?: 'Materia' }}</strong>
                                <div class="small text-muted">{{ $inscripcion->mesa->sede->nombre ?: 'Sede' }} · {{ $inscripcion->mesa->fecha?->format('d/m/Y') }}</div>
                                <form action="{{ route('cent.alumno.permisos.solicitar', $inscripcion->mesa) }}" method="POST" class="mt-3">
                                    @csrf
                                    <button class="btn btn-sm btn-primary"><i class="ti ti-qr-code me-1"></i> Solicitar permiso</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">No tenés mesas inscriptas.</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-xl-7">
        <div class="modern-card p-4 h-100">
            <h3 class="fw-bolder mb-4">Mis permisos</h3>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Código</th><th>Materia</th><th>Estado</th><th>Pago</th><th></th></tr></thead>
                    <tbody>
                    @forelse($permisos as $permiso)
                        @php($color = $permiso->estado === 'habilitado' ? 'success' : ($permiso->estado === 'anulado' ? 'danger' : 'warning'))
                        <tr>
                            <td class="fw-bold">{{ $permiso->codigo }}</td>
                            <td>
                                {{ $permiso->mesa->materia->name ?: 'Materia' }}
                                <div class="small text-muted">{{ $permiso->mesa->fecha?->format('d/m/Y') }}</div>
                            </td>
                            <td><span class="badge bg-{{ $color }}-subtle text-{{ $color }}">{{ ucfirst(str_replace('_', ' ', $permiso->estado)) }}</span></td>
                            <td>{{ $permiso->cuota?->estado ? ucfirst($permiso->cuota->estado) : 'Sin pago asociado' }}</td>
                            <td class="text-end">
                                @if($permiso->estado === 'habilitado')
                                    <a href="{{ route('cent.alumno.permisos.pdf', $permiso) }}" class="btn btn-sm btn-outline-primary"><i class="ti ti-printer me-1"></i> PDF</a>
                                @else
                                    <span class="small text-muted">Pendiente</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-muted">Todavía no solicitaste permisos.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
