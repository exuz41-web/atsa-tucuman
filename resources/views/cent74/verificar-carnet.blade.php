@extends('layouts.cent-public')

@section('title', 'Verificación de estudiante')

@section('content')
<section class="py-5 bg-light">
    <div class="container py-lg-5">
        <div class="card cent-card mx-auto" style="max-width: 760px;">
            <div class="card-body p-5 text-center">
                @if($alumno)
                    @php($matricula = $alumno->matriculasCent->first())
                    @php($dni = preg_replace('/\D+/', '', (string) $alumno->dni))
                    @php($dniMask = strlen($dni) >= 4 ? str_repeat('*', max(strlen($dni) - 4, 0)).substr($dni, -4) : ($alumno->dni ?: '-'))
                    <span class="feature-icon mx-auto mb-4 bg-success-subtle text-success"><i class="ti ti-circle-check"></i></span>
                    <h1 class="fw-bolder">Estudiante verificado</h1>
                    <p class="text-muted">Credencial oficial del CENT N°74.</p>
                    <div class="d-flex flex-column align-items-center gap-3 my-4">
                        @if($alumno->foto_perfil)
                            <img src="{{ asset('storage/'.$alumno->foto_perfil) }}" class="rounded-circle object-fit-cover" style="width:110px;height:110px;" alt="{{ $alumno->name }}">
                        @endif
                        <h2 class="fw-bolder mb-0">{{ $alumno->name }}</h2>
                    </div>
                    <div class="text-start bg-light rounded-4 p-4">
                        DNI: <strong>{{ $dniMask }}</strong><br>
                        Carrera: <strong>{{ $matricula?->carrera?->name ?: '-' }}</strong><br>
                        Sede: <strong>{{ $matricula?->sede?->nombre ?: 'CENT N°74' }}</strong>
                    </div>
                @else
                    <span class="feature-icon mx-auto mb-4 bg-danger-subtle text-danger"><i class="ti ti-x"></i></span>
                    <h1 class="fw-bolder">Estudiante no encontrado</h1>
                    <p class="text-muted mb-0">La credencial no corresponde a un alumno activo del CENT.</p>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
