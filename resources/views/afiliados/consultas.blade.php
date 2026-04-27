@extends('layouts.afiliado')

@section('title', 'Mis consultas')
@section('page_title', 'Mis consultas')

@section('content')
@php
    $badges = [
        'pendiente' => 'bg-warning',
        'confirmado' => 'bg-success',
        'rechazado' => 'bg-danger',
        'completado' => 'bg-secondary',
    ];
@endphp

<div class="row g-4">
    <div class="col-xl-7">
        <div class="portal-card p-4 p-lg-5 h-100">
            <div class="d-flex align-items-center gap-3 mb-4">
                <span class="portal-icon"><i class="ti ti-message-dots"></i></span>
                <div>
                    <p class="text-primary fw-semibold fs-3 mb-1">HISTORIAL</p>
                    <h2 class="fw-bolder mb-0">Consultas y turnos</h2>
                </div>
            </div>

            <div class="vstack gap-3">
                @forelse ($consultas as $consulta)
                    <article class="rounded-3 border p-4 bg-light">
                        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
                            <div>
                                <h5 class="fw-bolder mb-1">{{ $consulta->asunto }}</h5>
                                <p class="text-muted mb-0">{{ ucfirst($consulta->tipo) }} · {{ $consulta->created_at->format('d/m/Y') }}</p>
                            </div>
                            <span class="badge {{ $badges[$consulta->estado] ?? 'bg-primary' }} rounded-pill px-3 py-2 text-capitalize">{{ $consulta->estado }}</span>
                        </div>
                        <p class="text-muted mt-3 mb-0">{{ $consulta->mensaje }}</p>
                        @if ($consulta->respuesta)
                            <div class="mt-3 rounded-3 bg-white border p-3">
                                <p class="fw-bolder text-atsa-blue mb-1">Respuesta de ATSA</p>
                                <p class="text-muted mb-0">{{ $consulta->respuesta }}</p>
                            </div>
                        @endif
                    </article>
                @empty
                    <div class="text-center py-5">
                        <i class="ti ti-message-off text-muted fs-9 d-block mb-2"></i>
                        <h5 class="fw-bolder mb-1">Todavía no enviaste consultas</h5>
                        <p class="text-muted mb-0">Cuando envíes una consulta, vas a ver acá el seguimiento.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-xl-5">
        <div class="portal-card p-4 p-lg-5">
            <div class="d-flex align-items-center gap-3 mb-4">
                <span class="portal-icon"><i class="ti ti-send"></i></span>
                <div>
                    <p class="text-primary fw-semibold fs-3 mb-1">NUEVA CONSULTA</p>
                    <h2 class="fw-bolder mb-0">Escribinos</h2>
                </div>
            </div>

            <form method="POST" action="{{ route('afiliados.consultas.guardar') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tipo</label>
                    <select name="tipo" class="form-select" required>
                        <option value="consulta">Consulta</option>
                        <option value="turno">Turno</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Asunto</label>
                    <input name="asunto" value="{{ old('asunto') }}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Fecha solicitada</label>
                    <input type="date" name="fecha_solicitada" value="{{ old('fecha_solicitada') }}" class="form-control">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Mensaje</label>
                    <textarea name="mensaje" rows="6" class="form-control" required>{{ old('mensaje') }}</textarea>
                </div>
                <button class="btn btn-primary w-100 py-3 shadow-none">
                    <i class="ti ti-send me-2"></i>Enviar consulta
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
