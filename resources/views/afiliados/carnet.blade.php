@extends('layouts.afiliado')

@section('title', 'Mi Carnet Digital')
@section('page_title', 'Mi Carnet Digital')

@php
    use App\Models\SiteSetting;
    use App\Support\CarnetSupport;

    $fotoUrl = CarnetSupport::fotoUrl($afiliado);
    $iniciales = CarnetSupport::initials($afiliado->name);
    $vencido = $afiliado->carnet_vencimiento && $afiliado->carnet_vencimiento->lt(now()->startOfDay());
    $valido = $afiliado->carnet_activo && ! $vencido;
    $siteLogo = SiteSetting::logoUrl();
    $vencimiento = optional($afiliado->carnet_vencimiento)->format('d/m/Y') ?: '31/12/'.date('Y');
@endphp

@section('content')
<style>
    .carnet-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }
    .carnet-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 48px;
        border-radius: 8px;
        padding: 12px 20px;
        color: #fff;
        font-weight: 800;
        text-decoration: none;
        border: 0;
        white-space: nowrap;
        box-shadow: 0 8px 20px rgba(42, 53, 71, .10);
    }
    .carnet-action-btn:hover {
        color: #fff;
        opacity: .92;
    }
    .carnet-page-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 12px 24px -4px rgba(145, 158, 171, .12);
    }
    .carnet-scroll {
        overflow-x: auto;
        padding: 10px 4px 18px;
    }
    .carnet-card-stack {
        display: grid;
        width: min(100%, 680px);
        min-width: 0;
        justify-content: center;
        gap: 24px;
        margin: 0 auto;
    }
    .atsa-plastic-card {
        position: relative;
        width: min(100%, 620px);
        aspect-ratio: 1.586 / 1;
        min-height: 390px;
        overflow: hidden;
        border-radius: 16px;
        background: #fff;
        border: 1px solid #e5eaef;
        box-shadow: 0 18px 45px rgba(42, 53, 71, .18);
        font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        color: #2a3547;
    }
    .atsa-plastic-card::before {
        content: '';
        position: absolute;
        right: -70px;
        top: -90px;
        width: 220px;
        height: 220px;
        border-radius: 999px;
        background: #ecf2ff;
    }
    .atsa-card-logo {
        position: absolute;
        left: 28px;
        top: 20px;
        height: 44px;
        max-width: 170px;
        object-fit: contain;
    }
    .atsa-card-label {
        position: absolute;
        left: 28px;
        top: 76px;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: #5a6a85;
    }
    .atsa-card-type {
        position: absolute;
        right: 26px;
        top: 24px;
        border-radius: 8px;
        background: #1e3a5f;
        padding: 10px 16px;
        color: #fff;
        font-size: 12px;
        font-weight: 900;
        letter-spacing: .04em;
        text-transform: uppercase;
    }
    .atsa-card-photo {
        position: absolute;
        left: 32px;
        top: 118px;
        display: grid;
        width: 106px;
        height: 106px;
        place-items: center;
        overflow: hidden;
        border-radius: 999px;
        border: 6px solid #ecf2ff;
        background: #dfe7ff;
        color: #5d87ff;
        font-size: 34px;
        font-weight: 900;
    }
    .atsa-card-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .atsa-card-number-label {
        position: absolute;
        left: 30px;
        bottom: 92px;
        width: 118px;
        text-align: center;
        font-size: 10px;
        font-weight: 900;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: #5a6a85;
    }
    .atsa-card-number {
        position: absolute;
        left: 24px;
        bottom: 68px;
        width: 128px;
        text-align: center;
        color: #1e3a5f;
        font-size: 16px;
        font-weight: 900;
        white-space: nowrap;
    }
    .atsa-barcode {
        position: absolute;
        left: 42px;
        bottom: 50px;
        width: 90px;
        height: 14px;
        background: repeating-linear-gradient(90deg, #1e3a5f 0 2px, transparent 2px 5px, #1e3a5f 5px 7px, transparent 7px 12px);
        opacity: .8;
    }
    .atsa-card-status {
        position: absolute;
        left: 202px;
        top: 112px;
        border-radius: 999px;
        padding: 8px 14px;
        font-size: 11px;
        font-weight: 900;
        text-transform: uppercase;
    }
    .atsa-card-status.is-valid { background: #e6fffa; color: #13a384; }
    .atsa-card-status.is-invalid { background: #eaf8ff; color: #1e3a5f; }
    .atsa-card-name {
        position: absolute;
        left: 202px;
        right: 118px;
        top: 156px;
        font-size: 23px;
        line-height: 1.08;
        font-weight: 900;
        color: #2a3547;
    }
    .atsa-data-list {
        position: absolute;
        left: 202px;
        top: 215px;
        width: 240px;
        display: grid;
        gap: 6px;
        font-size: 13px;
        line-height: 1.15;
        margin: 0;
    }
    .atsa-data-row {
        display: grid;
        grid-template-columns: 76px 1fr;
        gap: 10px;
        min-height: 18px;
    }
    .atsa-data-row dt {
        color: #5a6a85;
        font-weight: 900;
        margin: 0;
        line-height: 1.15;
    }
    .atsa-data-row dd {
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-weight: 800;
        color: #2a3547;
        margin: 0;
        line-height: 1.15;
    }
    .atsa-card-qr {
        position: absolute;
        right: 26px;
        bottom: 70px;
        width: 82px;
        border-radius: 10px;
        border: 1px solid #dfe5ef;
        background: #fff;
        padding: 6px;
        text-align: center;
    }
    .atsa-card-qr img {
        width: 68px;
        height: 68px;
    }
    .atsa-card-qr span {
        display: block;
        margin-top: 3px;
        font-size: 9px;
        font-weight: 900;
        color: #5a6a85;
    }
    .atsa-sky-strip {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 40px;
        height: 6px;
        background: linear-gradient(90deg, #49beff 0%, #5d87ff 100%);
    }
    .atsa-blue-strip {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 40px;
        display: grid;
        place-items: center;
        background: linear-gradient(90deg, #0f2236 0%, #1e3a5f 62%, #5d87ff 100%);
        color: #fff;
        font-size: 10px;
        font-weight: 900;
        letter-spacing: .12em;
        text-align: center;
        text-transform: uppercase;
    }
    .atsa-back-title {
        position: absolute;
        left: 28px;
        top: 30px;
        font-size: 12px;
        font-weight: 900;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: #5a6a85;
    }
    .atsa-back-heading {
        position: absolute;
        left: 28px;
        top: 54px;
        font-size: 24px;
        font-weight: 900;
        color: #2a3547;
    }
    .atsa-back-info {
        position: absolute;
        left: 28px;
        top: 104px;
        width: 300px;
        display: grid;
        gap: 10px;
    }
    .atsa-back-box {
        border-radius: 10px;
        background: #f6f8fb;
        padding: 11px 14px;
    }
    .atsa-back-box strong {
        display: block;
        color: #1e3a5f;
        font-size: 13px;
        font-weight: 900;
    }
    .atsa-back-box span {
        display: block;
        margin-top: 3px;
        color: #5a6a85;
        font-size: 12px;
        font-weight: 700;
    }
    .atsa-signature {
        position: absolute;
        right: 28px;
        top: 112px;
        width: 160px;
        border-radius: 10px;
        background: #fff;
        border: 1px solid #e5eaef;
        padding: 12px;
        text-align: center;
    }
    .atsa-signature .line {
        height: 48px;
        border-bottom: 1px solid #dfe5ef;
        background: linear-gradient(145deg, transparent 0 36%, rgba(30, 58, 95, .24) 36% 38%, transparent 38% 100%), linear-gradient(20deg, transparent 0 48%, rgba(30, 58, 95, .22) 48% 50%, transparent 50% 100%);
    }
    .atsa-signature p {
        margin-top: 8px;
        color: #1e3a5f;
        font-size: 11px;
        font-weight: 900;
    }
    .atsa-back-qr {
        position: absolute;
        right: 52px;
        bottom: 70px;
        width: 112px;
        border-radius: 10px;
        background: #fff;
        border: 1px solid #dfe5ef;
        padding: 8px;
        text-align: center;
    }
    .atsa-back-qr img { width: 92px; height: 92px; }
    .carnet-side-panel {
        position: sticky;
        top: calc(var(--atsa-aff-header, 72px) + 24px);
    }
    @media (max-width: 767.98px) {
        .carnet-action-btn {
            width: 100%;
        }
        .atsa-plastic-card {
            min-height: 390px;
            width: 620px;
        }
        .carnet-card-stack {
            width: 620px;
        }
        .carnet-scroll {
            padding-left: 0;
            padding-right: 0;
        }
    }
</style>

<div class="mb-4 d-flex flex-column flex-xl-row align-items-xl-center justify-content-between gap-3">
    <div>
        <p class="fw-bold text-muted mb-1">Credencial digital verificable con QR</p>
        <h2 class="fw-bolder text-dark mb-0">Carnet de afiliado ATSA Tucumán</h2>
    </div>
    <div class="carnet-actions">
        <a href="{{ route('afiliado.carnet.descargar') }}" class="carnet-action-btn" style="background:#1e3a5f;">
            <i class="ti ti-download"></i>
            Descargar PDF
        </a>
        <a href="{{ route('afiliado.carnet.imagen') }}" class="carnet-action-btn" style="background:#5d87ff;">
            <i class="ti ti-photo"></i>
            Guardar imagen
        </a>
        <button type="button" id="btn-compartir" class="carnet-action-btn" style="background:#13a384;">
            <i class="ti ti-share"></i>
            Compartir
        </button>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="row g-4">
    <section class="col-12 col-xxl-8">
        <div class="carnet-page-card p-3 p-lg-4">
        <div class="carnet-scroll">
            <div class="carnet-card-stack">
                <article class="atsa-plastic-card">
                    <img src="{{ $siteLogo }}" alt="ATSA Tucumán" class="atsa-card-logo">
                    <div class="atsa-card-label">Credencial personal e intransferible</div>
                    <div class="atsa-card-type">Afiliado gremial</div>
                    <div class="atsa-card-photo">
                        @if ($fotoUrl)
                            <img src="{{ $fotoUrl }}" alt="Foto de {{ $afiliado->name }}">
                        @else
                            {{ $iniciales }}
                        @endif
                    </div>
                    <div class="atsa-card-number-label">N° carnet</div>
                    <div class="atsa-card-number">{{ $afiliado->numero_afiliado }}</div>
                    <div class="atsa-barcode"></div>
                    <div class="atsa-card-status {{ $valido ? 'is-valid' : 'is-invalid' }}">{{ $valido ? 'Carnet válido' : ($vencido ? 'Carnet vencido' : 'Inactivo') }}</div>
                    <div class="atsa-card-name">{{ $afiliado->name }}</div>
                    <dl class="atsa-data-list">
                        <div class="atsa-data-row"><dt>DNI</dt><dd>{{ $afiliado->dni ?: 'No registrado' }}</dd></div>
                        <div class="atsa-data-row"><dt>Filial</dt><dd>{{ $afiliado->filial?->name ?: 'Sede Central' }}</dd></div>
                        @if($afiliado->lugar_trabajo)
                        <div class="atsa-data-row"><dt>Trabajo</dt><dd>{{ $afiliado->lugar_trabajo }}</dd></div>
                        @endif
                        <div class="atsa-data-row"><dt>Vence</dt><dd>{{ $vencimiento }}</dd></div>
                    </dl>
                    <div class="atsa-card-qr">
                        <img src="data:image/png;base64,{{ $qrCode }}" alt="QR de verificación">
                        <span>Verificar</span>
                    </div>
                    <div class="atsa-sky-strip"></div>
                    <div class="atsa-blue-strip">Asociación de Trabajadores de la Sanidad Argentina - Tucumán</div>
                </article>

                <article class="atsa-plastic-card">
                    <div class="atsa-back-title">Datos de contacto</div>
                    <div class="atsa-back-heading">Atención al afiliado</div>
                    <div class="atsa-back-info">
                        <div class="atsa-back-box"><strong>Sede Central</strong><span>Paraguay y Thames, San Miguel de Tucumán</span></div>
                        <div class="atsa-back-box"><strong>Teléfono</strong><span>0381 4331665</span></div>
                        <div class="atsa-back-box"><strong>Horario</strong><span>Lunes a viernes de 8:00 a 16:00 hs</span></div>
                    </div>
                    <div class="atsa-signature">
                        <div class="line"></div>
                        <p>Secretaría General</p>
                    </div>
                    <div class="atsa-back-qr">
                        <img src="data:image/png;base64,{{ $qrCode }}" alt="QR de verificación">
                    </div>
                    <div class="atsa-sky-strip"></div>
                    <div class="atsa-blue-strip">Escanear el QR para validar esta credencial</div>
                </article>
            </div>
        </div>
        </div>
    </section>

    <aside class="col-12 col-xxl-4">
        <div class="carnet-side-panel d-grid gap-4">
        <section class="carnet-page-card p-4">
            <h3 class="fw-bolder text-dark mb-4">Estado del carnet</h3>
            <dl class="vstack gap-3 mb-0">
                <div class="d-flex justify-content-between gap-4">
                    <dt class="text-muted fw-semibold">Estado</dt>
                    <dd class="fw-bolder mb-0 {{ $valido ? 'text-success' : 'text-primary' }}">
                        {{ $valido ? 'Activo' : ($vencido ? 'Vencido' : 'Inactivo') }}
                    </dd>
                </div>
                <div class="d-flex justify-content-between gap-4">
                    <dt class="text-muted fw-semibold">N° afiliado</dt>
                    <dd class="fw-bold mb-0">{{ $afiliado->numero_afiliado }}</dd>
                </div>
                <div class="d-flex justify-content-between gap-4">
                    <dt class="text-muted fw-semibold">Emisión</dt>
                    <dd class="fw-bold mb-0">{{ optional($afiliado->carnet_emitido_at)->format('d/m/Y') ?: 'Pendiente' }}</dd>
                </div>
                <div class="d-flex justify-content-between gap-4">
                    <dt class="text-muted fw-semibold">Vencimiento</dt>
                    <dd class="fw-bold mb-0">{{ $vencimiento }}</dd>
                </div>
                <div class="d-flex justify-content-between gap-4">
                    <dt class="text-muted fw-semibold">Filial</dt>
                    <dd class="fw-bold mb-0 text-end">{{ $afiliado->filial?->name ?: 'Sede Central' }}</dd>
                </div>
                @if($afiliado->lugar_trabajo)
                <div class="d-flex justify-content-between gap-4">
                    <dt class="text-muted fw-semibold">Establecimiento</dt>
                    <dd class="fw-bold mb-0 text-end">{{ $afiliado->lugar_trabajo }}</dd>
                </div>
                @endif
                @if($afiliado->categoria_laboral)
                <div class="d-flex justify-content-between gap-4">
                    <dt class="text-muted fw-semibold">Categoría</dt>
                    <dd class="fw-bold mb-0 text-end">{{ $afiliado->categoria_laboral }}</dd>
                </div>
                @endif
                @if($afiliado->fecha_alta)
                <div class="d-flex justify-content-between gap-4">
                    <dt class="text-muted fw-semibold">Alta gremial</dt>
                    <dd class="fw-bold mb-0">{{ $afiliado->fecha_alta->format('d/m/Y') }}</dd>
                </div>
                @endif
            </dl>
        </section>

        <section class="carnet-page-card p-4">
            <h3 class="fw-bolder text-dark mb-4">Uso del carnet</h3>
            <ul class="vstack gap-3 text-muted mb-0 ps-3">
                <li><strong class="text-dark">Presentalo</strong> desde el celular o descargado en PDF.</li>
                <li><strong class="text-dark">El QR</strong> confirma si está activo y vigente.</li>
                <li><strong class="text-dark">Es válido</strong> para beneficios, trámites y acreditación.</li>
            </ul>
        </section>
        </div>
    </aside>
</div>

<section id="actualizar-foto" class="carnet-page-card mt-4 p-4">
    <h3 class="fw-bolder text-dark mb-4">Actualizar foto</h3>
    <div class="row g-4 align-items-center">
        <div class="col-auto">
        <div class="d-inline-grid overflow-hidden rounded-circle bg-light-primary text-primary fw-bolder" style="width:128px;height:128px;place-items:center;font-size:28px;">
            @if ($fotoUrl)
                <img src="{{ $fotoUrl }}" alt="Foto actual" class="w-100 h-100" style="object-fit:cover;">
            @else
                {{ $iniciales }}
            @endif
        </div>
        </div>
        <div class="col">
        <form method="POST" action="{{ route('afiliado.carnet.foto') }}" enctype="multipart/form-data" class="vstack gap-3">
            @csrf
            <div>
                <label class="form-label fw-bolder text-dark">Nueva foto</label>
                <input type="file" name="foto" accept="image/png,image/jpeg" required class="form-control">
                @error('foto') <p class="mt-2 text-danger fw-bold">{{ $message }}</p> @enderror
                <p class="mt-2 text-muted mb-0">JPG o PNG, máximo 2MB. Usar fondo neutro y rostro visible.</p>
            </div>
            <button class="btn btn-primary shadow-none align-self-start">Guardar foto</button>
        </form>
        </div>
    </div>
</section>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('btn-compartir');
    if (!btn) return;

    if (navigator.share) {
        btn.addEventListener('click', function () {
            navigator.share({
                title: 'Carnet ATSA Tucumán — {{ addslashes($afiliado->name) }}',
                text: 'Mi credencial de afiliado ATSA Tucumán N° {{ $afiliado->numero_afiliado }}',
                url: window.location.href,
            }).catch(() => {});
        });
    } else {
        // Fallback: copiar URL al portapapeles
        btn.addEventListener('click', function () {
            navigator.clipboard?.writeText(window.location.href).then(function () {
                btn.innerHTML = '<i class="ti ti-check"></i> ¡Enlace copiado!';
                setTimeout(() => {
                    btn.innerHTML = '<i class="ti ti-share"></i> Compartir';
                }, 2500);
            }).catch(() => {
                alert('Copiá este enlace: ' + window.location.href);
            });
        });
    }
});
</script>
@endpush
@endsection

