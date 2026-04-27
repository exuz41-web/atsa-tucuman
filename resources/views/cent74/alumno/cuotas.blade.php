@extends('layouts.cent')

@section('title', 'Cuotas y pagos')
@section('header', 'Cuotas')

@php
    $estadoColor = ['pendiente' => 'warning', 'pagada' => 'success', 'vencida' => 'danger', 'bonificada' => 'success', 'anulada' => 'secondary'];
@endphp

@section('content')
<section class="portal-hero p-4 p-xl-5 mb-4">
    <span class="badge bg-white text-primary mb-3">Administración</span>
    <h1 class="display-6 fw-bolder mb-3">Cuotas, descuentos y comprobantes</h1>
    <p class="fs-5 text-muted mb-0">Consultá cuotas, descuentos por afiliado ATSA o hijo de afiliado, vencimientos y comprobantes.</p>
</section>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-tile">
            <span class="stat-icon bg-danger-subtle text-danger mb-3"><i class="ti ti-alert-circle"></i></span>
            <h2 class="fw-bolder mb-1">${{ number_format($deudaTotal ? 0, 2, ',', '.') }}</h2>
            <p class="text-muted mb-0">Saldo pendiente</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-tile">
            <span class="stat-icon bg-warning-subtle text-warning mb-3"><i class="ti ti-calendar-due"></i></span>
            <h2 class="fw-bolder mb-1">{{ $cuotas->where('estado', 'vencida')->count() }}</h2>
            <p class="text-muted mb-0">Cuotas vencidas</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-tile">
            <span class="stat-icon bg-success-subtle text-success mb-3"><i class="ti ti-circle-check"></i></span>
            <h2 class="fw-bolder mb-1">{{ $cuotas->whereIn('estado', ['pagada', 'bonificada'])->count() }}</h2>
            <p class="text-muted mb-0">Pagos confirmados</p>
        </div>
    </div>
</div>

<div class="modern-card p-4">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Concepto</th>
                    <th>Monto</th>
                    <th>Descuento</th>
                    <th>Final</th>
                    <th>Vencimiento</th>
                    <th>Estado</th>
                    <th>Comprobante / recibo</th>
                </tr>
            </thead>
            <tbody>
            @forelse($cuotas as $cuota)
                @php($color = $estadoColor[$cuota->estado] ?? 'warning')
                <tr>
                    <td>
                        <strong>{{ $cuota->concepto }}</strong>
                        <div class="small text-muted">{{ $cuota->periodo ?: 'Sin período' }}</div>
                        @if($cuota->afiliadoDescuento)
                            <div class="small text-muted">Descuento por: {{ $cuota->afiliadoDescuento->name }} · {{ $cuota->afiliadoDescuento->numero_afiliado }}</div>
                        @endif
                    </td>
                    <td>${{ number_format($cuota->monto, 2, ',', '.') }}</td>
                    <td>
                        {{ ucfirst(str_replace('_', ' ', $cuota->descuento_tipo)) }}
                        @if($cuota->descuento_porcentaje > 0) · {{ $cuota->descuento_porcentaje }}% @endif
                    </td>
                    <td><strong>${{ number_format($cuota->monto_final, 2, ',', '.') }}</strong></td>
                    <td>{{ $cuota->vencimiento?->format('d/m/Y') ?: '-' }}</td>
                    <td><span class="badge bg-{{ $color }}-subtle text-{{ $color }}">{{ ucfirst($cuota->estado) }}</span></td>
                    <td>
                        @if($cuota->comprobante)
                            <a href="{{ asset('storage/'.$cuota->comprobante) }}" target="_blank" class="btn btn-sm btn-light">Ver</a>
                        @endif
                        @if($cuota->recibo)
                            <a href="{{ route('cent.alumno.recibos.pdf', $cuota->recibo) }}" target="_blank" class="btn btn-sm btn-success mt-2">
                                <i class="ti ti-receipt me-1"></i> Recibo
                            </a>
                        @endif
                        @if(! in_array($cuota->estado, ['pagada', 'bonificada', 'anulada'], true))
                            <form method="POST" action="{{ route('cent.alumno.cuotas.comprobante', $cuota) }}" enctype="multipart/form-data" class="mt-2">
                                @csrf
                                <input type="file" name="comprobante" class="form-control form-control-sm mb-2" accept=".pdf,.jpg,.jpeg,.png" required>
                                <button class="btn btn-sm btn-primary">Subir</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-muted text-center py-5">No hay cuotas cargadas para tu usuario.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

