@extends('layouts.cent-public')

@section('title', 'Estado de preinscripción CENT N°74')

@php
    $estadoColor = match ($preinscripcion->estado) {
        'inscripta', 'aprobada' => 'success',
        'rechazada' => 'danger',
        'en_revision' => 'info',
        default => 'warning',
    };

    $puedeActualizar = ! in_array($preinscripcion->estado, ['inscripta', 'rechazada'], true);
@endphp

@section('content')
<section class="py-5 bg-light">
    <div class="container py-lg-5">
        <div class="row g-4 align-items-start">
            <div class="col-lg-5">
                <div class="card cent-card">
                    <div class="card-body p-4 p-lg-5">
                        <span class="section-badge bg-{{ $estadoColor }}-subtle text-{{ $estadoColor }}">
                            {{ ucfirst(str_replace('_', ' ', $preinscripcion->estado)) }}
                        </span>
                        <h1 class="fw-bolder mb-3">Estado de solicitud</h1>
                        <p class="text-muted mb-4">Código: <strong>{{ $preinscripcion->codigo }}</strong></p>

                        <div class="d-flex flex-column gap-3">
                            <div class="p-3 rounded-4 bg-light">
                                <small class="text-muted d-block">Aspirante</small>
                                <strong>{{ $preinscripcion->apellido_nombre }}</strong>
                            </div>
                            <div class="p-3 rounded-4 bg-light">
                                <small class="text-muted d-block">Carrera</small>
                                <strong>{{ $preinscripcion->carrera->name }}</strong>
                            </div>
                            <div class="p-3 rounded-4 bg-light">
                                <small class="text-muted d-block">Sede</small>
                                <strong>{{ $preinscripcion->sede->nombre }}</strong>
                            </div>
                        </div>

                        <div class="cent-muted-box p-4 mt-4">
                            <div class="small text-uppercase fw-bold text-primary mb-2">Seguimiento</div>
                            <p class="text-muted mb-0">Usá este espacio para confirmar qué documentación quedó cargada y completar lo que la institución todavía tenga en revisión.</p>
                        </div>

                        <div class="d-flex gap-2 flex-wrap mt-4">
                            <a href="{{ route('cent.preinscripcion.ficha', $preinscripcion->public_token) }}" class="btn btn-cent">
                                <i class="ti ti-download me-1"></i> Ficha PDF
                            </a>
                            <a href="{{ route('cent.preinscripcion.consulta') }}" class="btn btn-light">Nueva consulta</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                @if($preinscripcion->observaciones_admin)
                    <div class="alert alert-warning rounded-4">
                        <h5 class="fw-bold"><i class="ti ti-alert-circle me-1"></i>Observación administrativa</h5>
                        <p class="mb-0">{{ $preinscripcion->observaciones_admin }}</p>
                    </div>
                @endif

                <div class="card cent-card mb-4">
                    <div class="card-body p-4 p-lg-5">
                        <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4">
                            <div>
                                <h3 class="fw-bolder mb-2">Documentación registrada</h3>
                                <p class="text-muted mb-0">Acá podés ver qué archivos ya quedaron asociados al trámite y cuáles siguen pendientes.</p>
                            </div>
                            <span class="badge bg-light text-dark border">DNI consultado: {{ $dniConsulta }}</span>
                        </div>

                        <div class="row g-3">
                            @foreach([
                                'archivo_dni' => 'DNI',
                                'archivo_titulo' => 'Título o constancia',
                                'archivo_recibo' => 'Recibo',
                                'archivo_adicional' => 'Archivo adicional',
                            ] as $field => $label)
                                <div class="col-md-6">
                                    <div class="p-3 rounded-4 bg-light h-100 d-flex justify-content-between align-items-center gap-3">
                                        <span>{{ $label }}</span>
                                        @if($preinscripcion->{$field})
                                            <span class="badge bg-success-subtle text-success">Presentado</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning">Pendiente</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @if($puedeActualizar)
                    <form action="{{ route('cent.preinscripcion.documentacion', $preinscripcion->codigo) }}" method="POST" enctype="multipart/form-data" class="card cent-card">
                        @csrf
                        <input type="hidden" name="dni" value="{{ $dniConsulta }}">
                        <div class="card-body p-4 p-lg-5">
                            <h3 class="fw-bolder mb-2">Actualizar documentación</h3>
                            <p class="text-muted mb-4">Usá este espacio si la sede te pidió corregir datos o adjuntar archivos faltantes. No hace falta iniciar una nueva preinscripción.</p>

                            @if($errors->any())
                                <div class="alert alert-danger rounded-4">Revisá los archivos o la observación cargada.</div>
                            @endif

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">DNI</label>
                                    <input type="file" name="archivo_dni" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Título/constancia</label>
                                    <input type="file" name="archivo_titulo" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Recibo</label>
                                    <input type="file" name="archivo_recibo" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Archivo adicional</label>
                                    <input type="file" name="archivo_adicional" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Observación para la sede</label>
                                    <textarea name="observaciones_alumno" rows="4" class="form-control" placeholder="Ej.: Adjunto constancia actualizada."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 p-4 p-lg-5 pt-0 text-end">
                            <button class="btn btn-cent">
                                <i class="ti ti-upload me-1"></i>Enviar actualización
                            </button>
                        </div>
                    </form>
                @else
                    <div class="card cent-card">
                        <div class="card-body p-4">
                            <p class="text-muted mb-0">Esta preinscripción ya no permite actualizaciones desde el sitio público.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
