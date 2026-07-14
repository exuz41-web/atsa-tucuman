@extends('layouts.afiliado')

@section('title', 'Mi Testimonio')
@section('page_title', 'Mi testimonio')

@section('content')
@php
    $fotoUrl = $user ? \App\Support\CarnetSupport::fotoUrl($user) : null;
    $iniciales = $user ? \App\Support\CarnetSupport::initials($user->name) : 'A';
    $estado = $testimonio?->estado ?: 'sin_enviar';
    $badgeClass = match ($estado) {
        'aprobado' => 'bg-success',
        'rechazado' => 'bg-danger',
        'pendiente' => 'bg-warning',
        default => 'bg-primary',
    };
    $estadoLabel = match ($estado) {
        'aprobado' => 'Publicado',
        'rechazado' => 'Rechazado',
        'pendiente' => 'Pendiente de revisión',
        default => 'Sin enviar',
    };
@endphp

<div class="row g-4">
    <div class="col-12">
        <div class="portal-card p-4 p-lg-5">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <p class="text-primary fw-semibold fs-3 mb-1">TESTIMONIOS</p>
                    <h2 class="fw-bolder mb-2">Contá tu experiencia como afiliado</h2>
                    <p class="text-muted mb-0">
                        Tu mensaje ayuda a mostrar el acompañamiento de ATSA Tucumán. Se publicará en el sitio cuando el equipo lo apruebe.
                    </p>
                </div>
                <div class="col-lg-4">
                    <div class="portal-card p-4 text-center shadow-none">
                        <span class="d-inline-grid overflow-hidden rounded-circle bg-primary-subtle text-primary fw-bolder mb-3" style="width:86px;height:86px;place-items:center;font-size:26px;">
                            @if ($fotoUrl)
                                <img src="{{ $fotoUrl }}" alt="Foto de {{ $user->name }}" class="w-100 h-100 object-fit-cover">
                            @else
                                {{ $iniciales }}
                            @endif
                        </span>
                        <h4 class="fw-bolder mb-1">{{ $user->name }}</h4>
                        <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2 mb-3">{{ $estadoLabel }}</span>
                        <button type="button" class="btn btn-primary w-100 shadow-none" onclick="document.getElementById('texto').focus()">
                            Escribir mensaje
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="portal-card p-4 p-lg-5 h-100">
            <h4 class="fw-bolder mb-1">Mi mensaje</h4>
            <p class="text-muted mb-4">Escribí un texto breve, claro y real. Máximo 500 caracteres.</p>

            <form method="POST" action="{{ route('afiliados.testimonio.guardar') }}">
                @csrf
                <div class="mb-4">
                    <label for="cargo" class="form-label fw-semibold">Cargo o lugar de trabajo</label>
                    <input
                        type="text"
                        id="cargo"
                        name="cargo"
                        class="form-control @error('cargo') is-invalid @enderror"
                        value="{{ old('cargo', $testimonio?->cargo ?: ($user->categoria_laboral ?: 'Afiliado')) }}"
                        placeholder="Ej: Enfermera, Hospital Padilla">
                    @error('cargo')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="texto" class="form-label fw-semibold">Testimonio</label>
                    <textarea
                        id="texto"
                        name="texto"
                        rows="7"
                        maxlength="500"
                        class="form-control @error('texto') is-invalid @enderror"
                        placeholder="Escribí tu experiencia con ATSA...">{{ old('texto', $testimonio?->texto ?: '') }}</textarea>
                    <div class="form-text">Tu testimonio aparecerá con tu nombre, foto de perfil y filial.</div>
                    @error('texto')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-3 flex-wrap">
                    <button type="submit" class="btn btn-primary px-4 shadow-none">
                        Enviar a revisión
                    </button>
                    <a href="{{ route('afiliados.dashboard') }}" class="btn btn-outline-primary px-4 shadow-none">
                        Volver al dashboard
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="portal-card p-4 h-100">
            <h4 class="fw-bolder mb-4">Vista previa</h4>
            <div class="border rounded-4 p-4 bg-light">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <span class="d-inline-grid overflow-hidden rounded-circle bg-primary-subtle text-primary fw-bolder flex-shrink-0" style="width:58px;height:58px;place-items:center;font-size:18px;">
                        @if ($fotoUrl)
                            <img src="{{ $fotoUrl }}" alt="Foto de {{ $user->name }}" class="w-100 h-100 object-fit-cover">
                        @else
                            {{ $iniciales }}
                        @endif
                    </span>
                    <div>
                        <h5 class="fw-bolder mb-1">{{ $user->name }}</h5>
                        <p class="mb-0 text-muted">{{ $testimonio?->cargo ?: ($user->categoria_laboral ?: 'Afiliado') }}</p>
                    </div>
                </div>
                <p class="text-muted fs-4 mb-3">"{{ $testimonio?->texto ?: 'Tu mensaje se verá así cuando lo escribas y sea aprobado.' }}"</p>
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">{{ $user->filial?->name ?: 'ATSA Tucumán' }}</span>
            </div>

            <div class="alert alert-info mt-4 mb-0">
                <strong>Importante:</strong> si modificás un testimonio ya aprobado, volverá a revisión antes de publicarse.
            </div>
        </div>
    </div>
</div>
@endsection
