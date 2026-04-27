@extends('layouts.cent')

@section('title', 'Avisos CENT')
@section('header', 'Avisos institucionales')

@section('content')
<section class="portal-hero p-4 p-xl-5 mb-4">
    <div class="row g-4 align-items-center">
        <div class="col-xl-8">
            <span class="badge bg-white text-primary mb-3">Comunicación interna</span>
            <h1 class="display-6 fw-bolder mb-3">Avisos para tu perfil</h1>
            <p class="fs-5 text-muted mb-0">
                Comunicaciones publicadas por carrera, sede o rol. Los avisos destacados aparecen primero.
            </p>
        </div>
        <div class="col-xl-4">
            <div class="modern-card p-4 text-dark">
                <div class="small text-muted">Perfil académico</div>
                <h3 class="fw-bolder mb-0">{{ ucfirst($centRole) }}</h3>
            </div>
        </div>
    </div>
</section>

<div class="row g-4">
    @forelse($avisos as $aviso)
        <div class="col-md-6 col-xl-4">
            <article class="modern-card p-4 h-100 {{ $aviso->destacado ? 'border border-primary border-2' : '' }}">
                <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                    <span class="stat-icon {{ $aviso->destacado ? 'bg-primary text-white' : 'bg-primary-subtle text-primary' }}">
                        <i class="ti ti-speakerphone"></i>
                    </span>
                    @if($aviso->destacado)
                        <span class="badge bg-primary text-white">Destacado</span>
                    @endif
                </div>
                <h3 class="h5 fw-bolder">{{ $aviso->titulo }}</h3>
                <p class="text-muted">{{ $aviso->contenido }}</p>
                <div class="small text-muted mt-auto">
                    <div><strong>Carrera:</strong> {{ $aviso->carrera?->name ?: 'Todas' }}</div>
                    <div><strong>Sede:</strong> {{ $aviso->sede?->nombre ?: 'Todas' }}</div>
                    <div><strong>Publicado:</strong> {{ $aviso->created_at->format('d/m/Y') }}</div>
                </div>
            </article>
        </div>
    @empty
        <div class="col-12">
            <div class="modern-card p-5 text-center">
                <span class="stat-icon bg-light text-muted mx-auto mb-3"><i class="ti ti-inbox"></i></span>
                <h3 class="fw-bolder">No hay avisos vigentes</h3>
                <p class="text-muted mb-0">Cuando Dirección publique novedades para tu rol, carrera o sede, van a aparecer acá.</p>
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $avisos->links('pagination::bootstrap-5') }}
</div>
@endsection
