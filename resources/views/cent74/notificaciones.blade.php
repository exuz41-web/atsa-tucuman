@extends('layouts.cent')

@section('title', 'Notificaciones')
@section('header', 'Notificaciones')

@php
    $tipoColor = [
        'cuota' => 'warning',
        'legajo' => 'info',
        'aula' => 'primary',
        'permiso' => 'success',
        'mesa' => 'success',
        'sistema' => 'secondary',
        'info' => 'primary',
    ];
@endphp

@section('content')
<section class="portal-hero p-4 p-xl-5 mb-4">
    <span class="badge bg-white text-primary mb-3">Centro de avisos</span>
    <h1 class="display-6 fw-bolder mb-3">Notificaciones internas</h1>
    <p class="fs-5 text-muted mb-0">Seguimiento de cuotas, legajo, aula virtual, permisos de examen y mensajes institucionales.</p>
</section>

<div class="modern-card p-4">
    @forelse($notificaciones as $notificacion)
        @php($color = $tipoColor[$notificacion->tipo] ?? 'primary')
        <div class="d-flex gap-3 align-items-start border-bottom py-3">
            <span class="stat-icon bg-{{ $color }}-subtle text-{{ $color }} flex-shrink-0">
                <i class="ti {{ $notificacion->leida_at ? 'ti-bell-check' : 'ti-bell-ringing' }}"></i>
            </span>
            <div class="flex-grow-1">
                <div class="d-flex flex-wrap gap-2 align-items-center mb-1">
                    <h5 class="fw-bolder mb-0">{{ $notificacion->titulo }}</h5>
                    <span class="badge bg-{{ $color }}-subtle text-{{ $color }}">{{ ucfirst($notificacion->tipo) }}</span>
                    @unless($notificacion->leida_at)
                        <span class="badge bg-danger-subtle text-danger">Nueva</span>
                    @endunless
                </div>
                <p class="text-muted mb-2">{{ $notificacion->mensaje }}</p>
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <span class="small text-muted">{{ $notificacion->created_at?->format('d/m/Y H:i') }}</span>
                    <form method="POST" action="{{ route('cent.notificaciones.leer', $notificacion) }}">
                        @csrf
                        <button class="btn btn-sm btn-primary">
                            {{ $notificacion->url ? 'Abrir' : 'Marcar como leída' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <span class="stat-icon bg-primary-subtle text-primary mb-3"><i class="ti ti-bell-off"></i></span>
            <h4 class="fw-bolder">Sin notificaciones</h4>
            <p class="text-muted mb-0">Cuando haya novedades académicas o administrativas, aparecerán acá.</p>
        </div>
    @endforelse

    <div class="mt-4">
        {{ $notificaciones->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
