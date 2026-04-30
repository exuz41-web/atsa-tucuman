@extends('layouts.prestador')

@section('title', 'Órdenes')

@php
    use App\Models\OrdenPrestacion;
@endphp

@section('content')
    <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 mb-4">
        <div>
            <p class="text-primary fw-bold fs-3 mb-1">ÓRDENES ASIGNADAS</p>
            <h2 class="fw-bolder mb-1">Panel de {{ $prestador->nombre }}</h2>
            <p class="text-muted mb-0">Desde acá se aceptan y registran entregas de órdenes emitidas por ATSA.</p>
        </div>
    </div>

    <div class="provider-card p-3 p-lg-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                <tr>
                    <th>Código</th>
                    <th>Afiliado</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Detalle</th>
                    <th class="text-end">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($ordenes as $orden)
                    <tr>
                        <td class="fw-bolder">{{ $orden->codigo }}</td>
                        <td>
                            <div class="fw-bolder">{{ $orden->afiliado?->name }}</div>
                            <div class="text-muted fs-2">{{ $orden->afiliado?->numero_afiliado ?: 'Sin número' }}</div>
                        </td>
                        <td>{{ OrdenPrestacion::tipos()[$orden->tipo] ?? ucfirst($orden->tipo) }}</td>
                        <td>
                            <span class="provider-badge badge-{{ $orden->estado }}">
                                {{ OrdenPrestacion::estados()[$orden->estado] ?? ucfirst($orden->estado) }}
                            </span>
                        </td>
                        <td class="text-muted" style="min-width:260px;">{{ $orden->detalle ?: 'Sin detalle' }}</td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-2 flex-wrap justify-content-end">
                                @if ($orden->estado === 'emitida')
                                    <form method="POST" action="{{ route('prestadores.ordenes.aceptar', [$prestador->portal_token, $orden]) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-primary shadow-none" type="submit">Aceptar</button>
                                    </form>
                                @endif

                                @if (in_array($orden->estado, ['emitida', 'aceptada', 'observada'], true))
                                    <a
                                        href="{{ route('prestadores.validar', ['token' => $prestador->portal_token, 'codigo' => $orden->codigo]) }}"
                                        class="btn btn-sm btn-success shadow-none"
                                    >
                                        <i class="ti ti-qrcode me-1"></i>Escanear QR
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="ti ti-clipboard-off text-muted fs-9 d-block mb-2"></i>
                            <h5 class="fw-bolder mb-1">No hay órdenes asignadas</h5>
                            <p class="text-muted mb-0">Cuando ATSA emita órdenes para este prestador van a aparecer acá.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
