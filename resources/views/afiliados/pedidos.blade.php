@extends('layouts.afiliado')

@section('title', 'Mis pedidos')
@section('page_title', 'Mis pedidos')

@section('content')
@php
    $badges = [
        'pendiente'   => 'bg-warning',
        'en_revision' => 'bg-info',
        'observado'   => 'bg-warning',
        'aprobado'    => 'bg-primary',
        'entregado'   => 'bg-success',
        'completado'  => 'bg-success',
        'rechazado'   => 'bg-danger',
    ];
    $estadoLabels = [
        'pendiente'   => 'Pendiente',
        'en_revision' => 'En revisión',
        'observado'   => 'Falta documentación',
        'aprobado'    => 'Aprobado',
        'entregado'   => 'Entregado',
        'completado'  => 'Completado',
        'rechazado'   => 'Rechazado',
    ];
@endphp

<div class="portal-card p-4 p-lg-5">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <p class="text-primary fw-semibold fs-3 mb-1">SOLICITUDES</p>
            <h2 class="fw-bolder mb-1">Mis pedidos</h2>
            <p class="text-muted mb-0">Seguimiento de pedidos sociales, documentación y observaciones administrativas.</p>
        </div>
        <a href="{{ route('afiliados.pedidos.nuevo') }}" class="btn btn-primary py-3 px-4 shadow-none">
            <i class="ti ti-circle-plus me-2"></i>Nuevo pedido
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
            <tr>
                <th class="text-muted fw-semibold">Tipo</th>
                <th class="text-muted fw-semibold">Descripción</th>
                <th class="text-muted fw-semibold">Estado</th>
                <th class="text-muted fw-semibold">Área</th>
                <th class="text-muted fw-semibold">Mensaje</th>
                <th class="text-muted fw-semibold">Fecha</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($pedidos as $pedido)
                <tr>
                    <td class="fw-bolder text-capitalize">{{ str_replace('_', ' ', $pedido->tipo) }}</td>
                    <td class="text-muted" style="min-width:260px;">{{ $pedido->descripcion }}</td>
                    <td>
                        <span class="badge {{ $badges[$pedido->estado] ?? 'bg-primary' }} rounded-pill px-3 py-2">
                            {{ $estadoLabels[$pedido->estado] ?? ucfirst(str_replace('_', ' ', $pedido->estado)) }}
                        </span>
                    </td>
                    <td class="text-muted">{{ $pedido->secretaria?->nombre ?: 'Recepción' }}</td>
                    <td class="text-muted">{{ $pedido->observacion_afiliado ?: 'Sin novedades' }}</td>
                    <td class="text-muted">{{ $pedido->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <i class="ti ti-folder-open text-muted fs-9 d-block mb-2"></i>
                        <h5 class="fw-bolder mb-1">Todavía no realizaste pedidos</h5>
                        <p class="text-muted mb-3">Cuando cargues una solicitud, vas a poder seguir su estado desde acá.</p>
                        <a href="{{ route('afiliados.pedidos.nuevo') }}" class="btn btn-primary shadow-none">Crear primer pedido</a>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
