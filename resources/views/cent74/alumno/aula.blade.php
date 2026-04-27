@extends('layouts.cent')

@section('title', 'Aula virtual CENT')
@section('header', 'Aula virtual')

@section('content')
<section class="portal-hero p-4 p-xl-5 mb-4">
    <div class="row g-4 align-items-center">
        <div class="col-lg-8">
            <span class="portal-kicker"><i class="ti ti-device-laptop"></i> Classroom CENT</span>
            <h1 class="display-6 fw-bolder mt-3 mb-3">Clases, materiales y trabajos prácticos</h1>
            <p class="fs-5 text-muted mb-0">Toda la actividad académica de tus comisiones en un solo lugar.</p>
        </div>
        <div class="col-lg-4">
            <div class="bg-white bg-opacity-10 rounded-4 p-4 border border-white border-opacity-25">
                <div class="fw-bold">Comisiones activas</div>
                <div class="display-6 fw-bolder">{{ $comisiones->count() }}</div>
            </div>
        </div>
    </div>
</section>

<div class="row g-4 mb-4">
    <div class="col-xl-5">
        <div class="modern-card p-4 h-100">
            <h3 class="fw-bolder mb-4">Próximas clases</h3>
            <div class="vstack gap-3">
                @forelse($clases as $clase)
                    <div class="d-flex gap-3 soft-panel p-3">
                        <span class="stat-icon bg-primary-subtle text-primary"><i class="ti ti-calendar-event"></i></span>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between gap-3">
                                <strong>{{ $clase->titulo }}</strong>
                                <span class="badge bg-primary-subtle text-primary align-self-start">{{ ucfirst($clase->modalidad) }}</span>
                            </div>
                            <div class="text-muted small">{{ $clase->comision->materia->name ?: 'Materia' }} · {{ $clase->fecha_inicio?->format('d/m/Y H:i') }}</div>
                            @if($clase->aula)<div class="small">Aula: {{ $clase->aula }}</div>@endif
                            @if($clase->link_virtual)
                                <a href="{{ $clase->link_virtual }}" target="_blank" class="btn btn-sm btn-primary mt-3"><i class="ti ti-video me-1"></i> Entrar</a>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">Todavía no hay clases publicadas.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-xl-7">
        <div class="modern-card p-4 h-100">
            <h3 class="fw-bolder mb-4">Material de estudio</h3>
            <div class="row g-3">
                @forelse($materiales as $material)
                    <div class="col-md-6">
                        <div class="soft-panel p-3 h-100">
                            <div class="d-flex align-items-start gap-3">
                                <span class="stat-icon bg-info-subtle text-info"><i class="ti ti-file-download"></i></span>
                                <div>
                                    <strong>{{ $material->titulo }}</strong>
                                    <div class="small text-muted">{{ ucfirst($material->tipo) }} · {{ $material->comision->materia->name ?: 'Materia' }}</div>
                                </div>
                            </div>
                            <div class="d-flex gap-2 mt-3 flex-wrap">
                                @if($material->archivo)
                                    <a class="btn btn-sm btn-outline-primary" target="_blank" href="{{ asset('storage/'.$material->archivo) }}">Descargar</a>
                                @endif
                                @if($material->url)
                                    <a class="btn btn-sm btn-primary" target="_blank" href="{{ $material->url }}">Abrir link</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">No hay materiales disponibles por ahora.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="modern-card p-4">
    <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h3 class="fw-bolder mb-1">Trabajos prácticos y entregas</h3>
            <p class="text-muted mb-0">Subí tus archivos, revisá vencimientos y devoluciones docentes.</p>
        </div>
    </div>
    <div class="row g-4">
        @forelse($trabajos as $trabajo)
            @php($entrega = $trabajo->entregas->first())
            <div class="col-lg-6">
                <div class="soft-panel p-4 h-100">
                    <div class="d-flex justify-content-between gap-3 mb-3">
                        <div>
                            <h4 class="fw-bolder mb-1">{{ $trabajo->titulo }}</h4>
                            <p class="text-muted mb-0">{{ $trabajo->comision->materia->name ?: 'Materia' }}</p>
                        </div>
                        <span class="badge {{ $entrega ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} align-self-start">
                            {{ $entrega ? ucfirst($entrega->estado) : 'Pendiente' }}
                        </span>
                    </div>
                    <p class="mb-3">{{ $trabajo->consigna }}</p>
                    <div class="small text-muted mb-3">Entrega: {{ $trabajo->fecha_entrega?->format('d/m/Y H:i') ?: 'Sin fecha límite' }}</div>
                    @if($trabajo->archivo_consigna)
                        <a href="{{ asset('storage/'.$trabajo->archivo_consigna) }}" target="_blank" class="btn btn-sm btn-outline-primary mb-3">Ver consigna</a>
                    @endif

                    @if($entrega)
                        <div class="alert alert-success rounded-4 mb-0">
                            Entregado el {{ $entrega->entregado_at?->format('d/m/Y H:i') ?: $entrega->created_at->format('d/m/Y H:i') }}.
                            @if($entrega->calificacion) Nota: {{ $entrega->calificacion }}. @endif
                            @if($entrega->devolucion)<br>Devolución: {{ $entrega->devolucion }}@endif
                        </div>
                    @elseif($trabajo->acepta_entregas)
                        <form action="{{ route('cent.alumno.trabajos.entregar', $trabajo) }}" method="POST" enctype="multipart/form-data" class="vstack gap-3">
                            @csrf
                            <textarea name="comentario" class="form-control" rows="2" placeholder="Comentario para el docente"></textarea>
                            <input type="file" name="archivo" class="form-control" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip">
                            <button class="btn btn-primary"><i class="ti ti-upload me-1"></i> Entregar trabajo</button>
                        </form>
                    @else
                        <div class="alert alert-warning rounded-4 mb-0">La entrega se encuentra cerrada.</div>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-muted mb-0">No hay trabajos prácticos publicados.</p>
        @endforelse
    </div>
</div>
@endsection
