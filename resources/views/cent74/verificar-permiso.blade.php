@extends('layouts.cent-public')

@section('title', 'Verificación de permiso')

@section('content')
<section class="py-5 bg-light">
    <div class="container py-lg-5">
        <div class="card cent-card mx-auto" style="max-width: 760px;">
            <div class="card-body p-5 text-center">
                @if($permiso)
                    <span class="feature-icon mx-auto mb-4 {{ $permiso->estado === 'habilitado' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }}">
                        <i class="ti {{ $permiso->estado === 'habilitado' ? 'ti-circle-check' : 'ti-alert-triangle' }}"></i>
                    </span>
                    <h1 class="fw-bolder">Permiso {{ $permiso->estado === 'habilitado' ? 'habilitado' : 'no habilitado' }}</h1>
                    <p class="text-muted">Código {{ $permiso->codigo }}</p>
                    <div class="text-start bg-light rounded-4 p-4 mt-4">
                        <strong>{{ $permiso->alumno->name }}</strong><br>
                        {{ $permiso->mesa->materia->name ?: 'Materia' }} · {{ $permiso->mesa->fecha?->format('d/m/Y') }}<br>
                        {{ $permiso->mesa->sede->nombre ?: 'CENT N°74' }}
                    </div>
                @else
                    <span class="feature-icon mx-auto mb-4 bg-danger-subtle text-danger"><i class="ti ti-x"></i></span>
                    <h1 class="fw-bolder">Permiso no encontrado</h1>
                    <p class="text-muted mb-0">El código QR no corresponde a un permiso registrado.</p>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
