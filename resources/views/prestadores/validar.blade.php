@extends('layouts.prestador')

@section('title', 'Validar afiliado')

@php
    use App\Models\OrdenPrestacion;
    use App\Support\CarnetSupport;

    $afiliadoValido = $afiliado
        && $afiliado->active
        && $afiliado->estado_afiliado === 'activo'
        && $afiliado->carnet_activo
        && (! $afiliado->carnet_vencimiento || $afiliado->carnet_vencimiento->gte(now()->startOfDay()));
@endphp

@section('content')
    <div class="row g-4 justify-content-center">
        <div class="col-lg-5 col-xl-4">
            <div class="provider-card provider-scan-card p-4">
                <p class="text-primary fw-bold fs-3 mb-1">VALIDACIÓN</p>
                <h2 class="h4 fw-bolder mb-3">Escanear QR del afiliado</h2>

                <form
                    method="GET"
                    action="{{ route('prestadores.validar', $prestador->portal_token) }}"
                    class="d-grid gap-3"
                    data-auto-scan="{{ request()->boolean('scan') ? '1' : '0' }}"
                >
                    <input id="qr-input" type="hidden" name="qr" value="{{ $busqueda['qr'] ?? '' }}">
                    @if (filled($busqueda['codigo'] ?? null))
                        <input type="hidden" name="codigo" value="{{ $busqueda['codigo'] }}">
                    @endif

                    <div class="d-grid gap-2">
                        <button id="start-scanner" class="btn btn-primary btn-lg shadow-none" type="button">
                            <i class="ti ti-camera me-2"></i>Abrir lector QR
                        </button>
                        <button id="retry-scanner" class="btn btn-outline-primary shadow-none d-none" type="button">
                            <i class="ti ti-refresh me-2"></i>Volver a escanear
                        </button>
                        <button id="capture-scanner" class="btn btn-primary btn-lg shadow-none d-none" type="button">
                            <i class="ti ti-camera-up me-2"></i>Tomar foto del QR
                        </button>
                        <input id="qr-image-input" class="d-none" type="file" accept="image/*" capture="environment">
                    </div>

                    <div id="scanner-panel" class="d-none">
                        <video id="qr-video" class="provider-scan-video w-100 rounded-3 bg-dark" playsinline muted></video>
                        <canvas id="qr-canvas" class="d-none"></canvas>
                        <button id="stop-scanner" class="btn btn-sm btn-light mt-2 w-100" type="button">Detener cámara</button>
                    </div>
                    <div id="scanner-help" class="provider-help-card small text-muted p-3">
                        Apuntá la cámara al QR del carnet digital del afiliado. Al detectarlo, el sistema valida automáticamente.
                    </div>
                    <div id="scanner-error" class="alert alert-warning border-0 d-none mb-0"></div>

                    <div class="border-top pt-3">
                        <button class="provider-manual-toggle btn btn-link p-0 small fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#manual-search">
                            Búsqueda manual de respaldo
                        </button>
                        <div id="manual-search" class="collapse mt-3">
                            <div class="d-grid gap-3">
                                <div>
                                    <label class="form-label fw-bold">N° afiliado</label>
                                    <input class="form-control" name="numero_afiliado" value="{{ $busqueda['numero_afiliado'] ?? '' }}" placeholder="Ej: 12345">
                                </div>
                                <div>
                                    <label class="form-label fw-bold">DNI</label>
                                    <input class="form-control" name="dni" value="{{ $busqueda['dni'] ?? '' }}" placeholder="Ej: 30111222">
                                </div>
                                <div>
                                    <label class="form-label fw-bold">Código de orden</label>
                                    <input class="form-control" name="codigo" value="{{ $busqueda['codigo'] ?? '' }}" placeholder="ORD-2026-000001">
                                </div>
                                <button class="btn btn-outline-primary shadow-none" type="submit">
                                    <i class="ti ti-search me-2"></i>Buscar manualmente
                                </button>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('prestadores.portal', $prestador->portal_token) }}" class="btn btn-light shadow-none">Volver a órdenes</a>
                </form>
            </div>
        </div>

        <div class="col-lg-7 col-xl-8">
            <div class="provider-card p-4 mb-4">
                @if (! array_filter($busqueda))
                    <div class="text-center py-4">
                        <i class="ti ti-qrcode text-muted fs-10 d-block mb-2"></i>
                        <h4 class="fw-bolder mb-1">Escaneá el QR del carnet</h4>
                        <p class="text-muted mb-0">La validación se completa automáticamente cuando la cámara lee el QR del afiliado.</p>
                    </div>
                @elseif (($ordenSeleccionada ?? null) && ! filled($busqueda['qr'] ?? null) && blank($busqueda['numero_afiliado'] ?? null) && blank($busqueda['dni'] ?? null))
                    <div class="text-center py-4">
                        <i class="ti ti-qrcode text-muted fs-10 d-block mb-2"></i>
                        <h4 class="fw-bolder mb-1">Validá el QR para entregar</h4>
                        <p class="text-muted mb-2">Orden {{ $ordenSeleccionada->codigo }}</p>
                        <p class="text-muted mb-0">Todavía no se muestra el afiliado hasta leer el QR del carnet.</p>
                    </div>
                @elseif (! $afiliado)
                    <div class="alert alert-danger border-0 mb-0">
                        No se encontró un afiliado con los datos ingresados.
                    </div>
                @else
                    <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
                        <div class="rounded-circle bg-light d-grid place-items-center overflow-hidden" style="width:88px;height:88px;">
                            @if (CarnetSupport::fotoUrl($afiliado))
                                <img src="{{ CarnetSupport::fotoUrl($afiliado) }}" alt="Foto" class="w-100 h-100 object-fit-cover">
                            @else
                                <span class="fw-bolder fs-6 text-primary">{{ CarnetSupport::initials($afiliado->name) }}</span>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <span class="provider-badge {{ $afiliadoValido ? 'badge-entregada' : 'badge-anulada' }}">
                                {{ $afiliadoValido ? 'AFILIADO HABILITADO' : 'AFILIADO NO HABILITADO' }}
                            </span>
                            <h3 class="fw-bolder mt-2 mb-1">{{ $afiliado->name }}</h3>
                            <p class="text-muted mb-0">
                                N° {{ $afiliado->numero_afiliado ?: 'sin número' }} · DNI {{ CarnetSupport::maskedDni($afiliado->dni) }} · {{ $afiliado->filial?->name ?: 'Central' }}
                            </p>
                        </div>
                    </div>

                    @if (! $afiliadoValido)
                        <div class="alert alert-warning border-0 mt-4 mb-0">
                            El afiliado no está activo, no tiene carnet vigente o no está habilitado para entrega.
                        </div>
                    @endif
                @endif
            </div>

            @if ($afiliado)
                <div class="provider-card p-4">
                    <div class="d-flex justify-content-between gap-3 mb-3">
                        <div>
                            <p class="text-primary fw-bold fs-3 mb-1">ÓRDENES VIGENTES</p>
                            <h4 class="fw-bolder mb-0">Para este prestador</h4>
                        </div>
                    </div>

                    @forelse ($ordenes as $orden)
                        <div class="border rounded-3 p-3 mb-3">
                            <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                                <div>
                                    <h5 class="fw-bolder mb-1">{{ $orden->codigo }}</h5>
                                    <p class="text-muted mb-2">{{ $orden->detalle ?: 'Sin detalle' }}</p>
                                    <span class="provider-badge badge-{{ $orden->estado }}">
                                        {{ OrdenPrestacion::estados()[$orden->estado] ?? ucfirst($orden->estado) }}
                                    </span>
                                </div>
                                @if ($afiliadoValido && in_array($orden->estado, ['emitida', 'aceptada', 'observada'], true))
                                    <form method="POST" action="{{ route('prestadores.ordenes.entregar', [$prestador->portal_token, $orden]) }}" class="d-grid gap-2" style="min-width:260px;">
                                        @csrf
                                        <input type="hidden" name="qr" value="{{ $busqueda['qr'] ?? '' }}">
                                        <textarea class="form-control" name="respuesta_prestador" rows="2" placeholder="Observación de entrega"></textarea>
                                        @if (filled($busqueda['qr'] ?? null))
                                            <button class="btn btn-success shadow-none" type="submit">
                                                Registrar entrega
                                            </button>
                                        @else
                                            <a href="{{ route('prestadores.validar', ['token' => $prestador->portal_token, 'codigo' => $orden->codigo, 'scan' => 1]) }}" class="btn btn-primary shadow-none">
                                                <i class="ti ti-qrcode me-2"></i>Validar QR para entregar
                                            </a>
                                            <p class="small text-muted mb-0">La entrega se habilita después de validar el QR del carnet.</p>
                                        @endif
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-warning border-0 mb-0">
                            No hay órdenes vigentes para este afiliado en este prestador.
                        </div>
                    @endforelse
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/prestador-qr.js')
@endpush
