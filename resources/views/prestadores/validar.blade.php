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
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="provider-card p-4">
                <p class="text-primary fw-bold fs-3 mb-1">VALIDACIÓN</p>
                <h2 class="h4 fw-bolder mb-3">Escanear QR del afiliado</h2>

                <form method="GET" action="{{ route('prestadores.validar', $prestador->portal_token) }}" class="d-grid gap-3">
                    <input id="qr-input" type="hidden" name="qr" value="{{ $busqueda['qr'] ?? '' }}">

                    <div class="d-grid gap-2">
                        <button id="start-scanner" class="btn btn-primary btn-lg shadow-none" type="button">
                            <i class="ti ti-camera me-2"></i>Abrir lector QR
                        </button>
                        <button id="retry-scanner" class="btn btn-outline-primary shadow-none d-none" type="button">
                            <i class="ti ti-refresh me-2"></i>Volver a escanear
                        </button>
                    </div>

                    <div id="scanner-panel" class="d-none">
                        <video id="qr-video" class="w-100 rounded-3 bg-dark" playsinline muted style="aspect-ratio: 4 / 3; object-fit: cover;"></video>
                        <button id="stop-scanner" class="btn btn-sm btn-light mt-2 w-100" type="button">Detener cámara</button>
                    </div>
                    <div id="scanner-help" class="small text-muted">
                        Apuntá la cámara al QR del carnet digital del afiliado. Al detectarlo, el sistema valida automáticamente.
                    </div>
                    <div id="scanner-error" class="alert alert-warning border-0 d-none mb-0"></div>

                    <div class="border-top pt-3">
                        <button class="btn btn-link p-0 text-muted small fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#manual-search">
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

        <div class="col-lg-8">
            <div class="provider-card p-4 mb-4">
                @if (! array_filter($busqueda))
                    <div class="text-center py-4">
                        <i class="ti ti-qrcode text-muted fs-10 d-block mb-2"></i>
                        <h4 class="fw-bolder mb-1">Escaneá el QR del carnet</h4>
                        <p class="text-muted mb-0">La validación se completa automáticamente cuando la cámara lee el QR del afiliado.</p>
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
                                        <button class="btn btn-success shadow-none" type="submit" @disabled(blank($busqueda['qr'] ?? null))>
                                            Registrar entrega
                                        </button>
                                        @if (blank($busqueda['qr'] ?? null))
                                            <p class="small text-muted mb-0">Para entregar, primero escaneá el QR del carnet del afiliado.</p>
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
<script>
    const qrInput = document.getElementById('qr-input');
    const startScanner = document.getElementById('start-scanner');
    const retryScanner = document.getElementById('retry-scanner');
    const stopScanner = document.getElementById('stop-scanner');
    const scannerPanel = document.getElementById('scanner-panel');
    const scannerError = document.getElementById('scanner-error');
    const qrVideo = document.getElementById('qr-video');
    let scannerStream = null;
    let scannerTimer = null;

    const showScannerError = (message) => {
        if (!scannerError) {
            return;
        }

        scannerError.textContent = message;
        scannerError.classList.remove('d-none');
    };

    const clearScannerError = () => {
        scannerError?.classList.add('d-none');
    };

    const stopCamera = () => {
        if (scannerTimer) {
            clearInterval(scannerTimer);
            scannerTimer = null;
        }

        if (scannerStream) {
            scannerStream.getTracks().forEach((track) => track.stop());
            scannerStream = null;
        }

        scannerPanel?.classList.add('d-none');
    };

    const submitWithQr = (value) => {
        if (!value) {
            return;
        }

        qrInput.value = value;
        stopCamera();
        qrInput.closest('form').submit();
    };

    const startCamera = async () => {
        clearScannerError();

        if (!('BarcodeDetector' in window) || !navigator.mediaDevices?.getUserMedia) {
            showScannerError('Este navegador no permite lector QR en vivo. Probá con Chrome desde el celular o usá la búsqueda manual de respaldo.');
            return;
        }

        try {
            scannerStream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'environment' },
                audio: false,
            });

            qrVideo.srcObject = scannerStream;
            await qrVideo.play();
            scannerPanel.classList.remove('d-none');
            startScanner?.classList.add('d-none');
            retryScanner?.classList.add('d-none');

            const detector = new BarcodeDetector({ formats: ['qr_code'] });
            scannerTimer = setInterval(async () => {
                try {
                    const codes = await detector.detect(qrVideo);
                    if (codes.length > 0) {
                        submitWithQr(codes[0].rawValue);
                    }
                } catch (error) {
                    stopCamera();
                }
            }, 650);
        } catch (error) {
            showScannerError('No se pudo abrir la cámara. Permití el acceso a cámara del navegador y volvé a intentar.');
            retryScanner?.classList.remove('d-none');
        }
    };

    startScanner?.addEventListener('click', startCamera);
    retryScanner?.addEventListener('click', startCamera);

    stopScanner?.addEventListener('click', () => {
        stopCamera();
        startScanner?.classList.remove('d-none');
        retryScanner?.classList.add('d-none');
    });
    window.addEventListener('beforeunload', stopCamera);

    if (new URLSearchParams(window.location.search).get('scan') === '1') {
        startCamera();
    }
</script>
@endpush
