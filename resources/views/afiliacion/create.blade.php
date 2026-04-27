@extends('layouts.app')

@section('title', 'Solicitud de Afiliación | ATSA Tucumán')

@push('styles')
<style>
    .afil-step {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 14px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .afil-step:last-child {
        border-bottom: none;
    }

    .afil-step-num {
        width: 34px;
        height: 34px;
        flex-shrink: 0;
        border-radius: 10px;
        background: #1e3a5f;
        color: #fff;
        font-size: 14px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Outfit', sans-serif;
    }

    .afil-step-title {
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 3px;
    }

    .afil-step-desc {
        font-size: 13px;
        color: #64748b;
        line-height: 1.5;
        margin: 0;
    }

    .afil-benefit {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .afil-benefit:last-child {
        border-bottom: none;
    }

    .afil-benefit-icon {
        width: 36px;
        height: 36px;
        flex-shrink: 0;
        border-radius: 10px;
        background: #ecf2ff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: #1e3a5f;
    }

    .afil-benefit-text {
        font-size: 13px;
        font-weight: 600;
        color: #2a3547;
        line-height: 1.4;
    }

    .afil-testimonial {
        background: linear-gradient(135deg, #1e3a5f 0%, #163050 100%);
        border-radius: 16px;
        padding: 20px;
        color: #fff;
    }

    .afil-testimonial-quote {
        font-size: 13px;
        line-height: 1.6;
        color: rgba(255, 255, 255, .82);
        font-style: italic;
        margin-bottom: 14px;
    }

    .afil-testimonial-author {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .afil-testimonial-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #49beff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 13px;
        color: #fff;
        flex-shrink: 0;
    }

    .afil-testimonial-name {
        font-size: 13px;
        font-weight: 700;
        color: #fff;
    }

    .afil-testimonial-role {
        font-size: 12px;
        color: rgba(255, 255, 255, .6);
    }

    .afil-section-label {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: #49beff;
        margin-bottom: 6px;
    }

    .afil-section-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 0;
        font-family: 'Outfit', sans-serif;
    }

    .afil-print-note {
        border: 1px dashed #b6c7df;
        background: #f8fbff;
    }

    .afil-preview-shell {
        background: #f4f7fb;
        border: 1px solid #d9e5f2;
    }

    /* ══════════════════════════════════════════════════════
       VISTA PREVIA — FORMULARIO IMPRIMIBLE A4
       Membrete único · Campos grid · Firmas compactas
    ══════════════════════════════════════════════════════ */

    .afil-preview-page {
        width: 190mm;
        max-width: 100%;
        margin: 0 auto;
        background: #fff;
        border: 1px solid #cbd5e1;
        box-shadow: 0 8px 40px rgba(15,35,54,.18);
        color: #111;
        font-family: "Times New Roman", Times, serif;
        position: relative;
        display: flex;
        flex-direction: column;
        /* screen: simula proporciones A4 pero no más */
        min-height: 248mm;
    }

    /* ── Marca de agua ── */
    .afil-wm {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%,-50%);
        width: 80mm;
        opacity: .04;
        pointer-events: none;
        z-index: 0;
    }

    /* ── MEMBRETE (único bloque) ── */
    .afil-hdr { position: relative; z-index: 1; }

    .afil-hdr-strip {
        background: #1e3a5f;
        color: #fff;
        font-size: 5.5pt;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .1em;
        text-align: center;
        padding: 2px 10mm;
        line-height: 1.3;
    }

    .afil-hdr-strip span {
        color: #93c5fd;
        font-weight: 400;
        letter-spacing: .08em;
    }

    .afil-hdr-main {
        display: grid;
        grid-template-columns: 18mm 1fr;
        gap: 4mm;
        align-items: center;
        padding: 4mm 10mm 3mm;
    }

    .afil-hdr-logo { width: 16mm; height: auto; }

    .afil-hdr-name {
        font-size: 12.5pt;
        font-weight: 700;
        color: #1e3a5f;
        text-transform: uppercase;
        letter-spacing: .05em;
        line-height: 1.15;
    }

    .afil-hdr-leg {
        font-size: 7pt;
        color: #374151;
        font-weight: 600;
        margin-top: 2px;
        line-height: 1.3;
    }

    .afil-hdr-contact {
        font-size: 6pt;
        color: #6b7280;
        margin-top: 2px;
    }

    .afil-hdr-border {
        height: 3px;
        background: linear-gradient(to right, #1e3a5f 70%, #49beff 100%);
    }

    /* ── Barra título + fecha ── */
    .afil-title-bar {
        background: #eef2f7;
        border-bottom: 1px solid #c8d8ea;
        padding: 4.5px 10mm;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        z-index: 1;
    }

    .afil-title-bar-name {
        font-size: 9pt;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .15em;
        color: #1e3a5f;
    }

    .afil-title-bar-date {
        font-size: 7.5pt;
        color: #374151;
        font-style: italic;
    }

    /* ── Cuerpo del formulario ── */
    .afil-body {
        padding: 4mm 10mm 5mm;
        display: flex;
        flex-direction: column;
        flex: 1;
        position: relative;
        z-index: 1;
    }

    /* ── Etiqueta de sección interna ── */
    .afil-sec-lbl {
        font-size: 5.5pt;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .14em;
        color: #1e3a5f;
        border-bottom: 1px solid #1e3a5f;
        padding-bottom: 1px;
        margin-bottom: 2.5mm;
    }

    .afil-sec-lbl + .afil-sec-lbl,
    .afil-sec-lbl.mt { margin-top: 3.5mm; }

    /* ── Grid de campos ── */
    .afil-fg {
        display: grid;
        gap: 2.5mm 3mm;
        margin-bottom: 2.5mm;
    }

    .afil-fg.c1  { grid-template-columns: 1fr; }
    .afil-fg.c2  { grid-template-columns: 1fr 1fr; }
    .afil-fg.c3  { grid-template-columns: 1fr 1fr 1fr; }
    .afil-fg.c4  { grid-template-columns: 1fr 1fr 1fr 1fr; }
    .afil-fg.c21 { grid-template-columns: 2fr 1fr; }
    .afil-fg.c211 { grid-template-columns: 2fr 1fr 1fr; }
    .afil-fg.c212 { grid-template-columns: 2fr 1fr 1.3fr; }

    .afil-f { display: flex; flex-direction: column; }

    .afil-fl {
        font-size: 6pt;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #6b7280;
        line-height: 1.5;
        white-space: nowrap;
    }

    .afil-fv {
        border-bottom: 1px solid #374151;
        min-height: 15px;
        line-height: 15px;
        padding: 0 2px 1px;
        font-size: 8.5pt;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* ── Texto de autorización ── */
    .afil-auth {
        margin: 3mm 0 3mm;
        font-size: 8pt;
        line-height: 1.45;
        text-align: justify;
        padding: 2.5mm 4mm;
        border: 1px solid #d1d5db;
        border-left: 3.5px solid #1e3a5f;
        background: #f8fafc;
    }

    /* ── Sección de firmas ── */
    .afil-sigs {
        margin-top: 3mm;
    }

    .afil-sigs-ttl {
        font-size: 5.5pt;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .14em;
        color: #1e3a5f;
        border-bottom: 1px solid #1e3a5f;
        padding-bottom: 1px;
        margin-bottom: 3mm;
    }

    .afil-sigs-row {
        display: grid;
        gap: 5mm;
        margin-bottom: 3mm;
    }

    .afil-sigs-row.c1 { grid-template-columns: 1fr; }
    .afil-sigs-row.c3 { grid-template-columns: 1fr 1fr 1fr; }

    .afil-sig { display: flex; flex-direction: column; }

    .afil-sig-sp {
        min-height: 13mm;
        border-bottom: 1px solid #374151;
        margin-bottom: 3px;
    }

    .afil-sig-sp.tall { min-height: 16mm; }

    .afil-sig-cap {
        text-align: center;
        font-size: 7pt;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #374151;
        line-height: 1.4;
    }

    .afil-sig-sub {
        text-align: center;
        font-size: 6pt;
        color: #9ca3af;
        font-style: italic;
    }

    /* ── Banda "Uso Oficial" ── */
    .afil-oficial-band {
        margin-top: 3mm;
        border: 1px dashed #d1d5db;
        border-radius: 2px;
        padding: 2mm 4mm;
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 4mm;
        background: #f9fafb;
    }

    .afil-oficial-f { display: flex; flex-direction: column; }

    .afil-oficial-lbl {
        font-size: 5.5pt;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: #9ca3af;
        line-height: 1.5;
    }

    .afil-oficial-val {
        border-bottom: 1px dotted #d1d5db;
        min-height: 11px;
        line-height: 11px;
        font-size: 7pt;
    }

    /* ── Pie del documento ── */
    .afil-footer-doc {
        margin-top: 2.5mm;
        padding-top: 2mm;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        font-size: 5.5pt;
        color: #9ca3af;
        font-style: italic;
    }

    /* legacy — ocultos */
    .afil-inline-row,
    .afil-inline-label,
    .afil-inline-value    { display: none !important; }

    /* ── Responsive pantalla (solo elementos de UI, nunca el documento) ── */
    /* El preview es un documento — sus grillas NO colapsan en pantalla chica */
    .afil-preview-page * { box-sizing: border-box; }

    /* Forzar que las grillas del documento respeten sus columnas */
    .afil-fg.c4  { grid-template-columns: 1fr 1fr 1fr 1fr !important; }
    .afil-fg.c3  { grid-template-columns: 1fr 1fr 1fr !important; }
    .afil-fg.c21 { grid-template-columns: 2fr 1fr !important; }
    .afil-fg.c211 { grid-template-columns: 2fr 1fr 1fr !important; }
    .afil-fg.c212 { grid-template-columns: 2fr 1fr 1.3fr !important; }
    .afil-sigs-row.c3 { grid-template-columns: 1fr 1fr 1fr !important; }
    .afil-oficial-band { grid-template-columns: 1fr 1fr 1fr !important; }

    @media (max-width: 600px) {
        /* Solo en mobile muy chico permitimos un poco de reflow */
        .afil-preview-page { width: 100%; font-size: 7pt; }
        .afil-hdr-main { grid-template-columns: 12mm 1fr !important; gap: 2mm; }
    }

    /* ══ PRINT — una sola hoja A4, sin páginas en blanco ══ */
    @media print {
        @page {
            size: A4 portrait;
            margin: 0.8cm;
        }

        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }

        /* Ocultar todo el documento */
        body > * { display: none !important; }

        /* Mostrar solo el clon inyectado por JS (hijo directo del body) */
        #printClone {
            display: block !important;
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
        }

        /* Página = área útil exacta (~ 192 × 281 mm) */
        #printClone .afil-preview-page {
            width: 190mm !important;
            height: 281mm !important;
            max-height: 281mm !important;
            min-height: 281mm !important;
            border: none !important;
            box-shadow: none !important;
            margin: 0 auto !important;
            overflow: hidden !important;
        }

        /* Compactar verticales para asegurar una sola hoja */
        #printClone .afil-hdr-main     { padding: 3mm 10mm 2mm !important; }
        #printClone .afil-title-bar    { padding: 3px 10mm !important; }
        #printClone .afil-body         { padding: 3mm 10mm 2mm !important; }
        #printClone .afil-sec-lbl      { margin-bottom: 2mm !important; }
        #printClone .afil-sec-lbl.mt   { margin-top: 2.5mm !important; }
        #printClone .afil-auth         { margin: 2mm 0 !important; padding: 2mm 3mm !important; font-size: 7.5pt !important; line-height: 1.35 !important; }
        #printClone .afil-sigs         { margin-top: 2mm !important; }
        #printClone .afil-sigs-ttl     { margin-bottom: 2mm !important; }
        #printClone .afil-sig-sp       { min-height: 11mm !important; }
        #printClone .afil-sig-sp.tall  { min-height: 13mm !important; }
        #printClone .afil-oficial-band { margin-top: 2mm !important; padding: 1.5mm 3mm !important; }
        #printClone .afil-footer-doc   { margin-top: 1.5mm !important; padding-top: 1mm !important; }

        /* Forzar fondos e impresión de color */
        #printClone .afil-hdr-strip    { background: #1e3a5f !important; color: #fff !important; }
        #printClone .afil-hdr-border   { background: linear-gradient(to right, #1e3a5f 70%, #49beff 100%) !important; }
        #printClone .afil-title-bar    { background: #eef2f7 !important; }
        #printClone .afil-auth         { background: #f8fafc !important; border-left-color: #1e3a5f !important; }
        #printClone .afil-oficial-band { background: #f9fafb !important; }

        #printClone .afil-fv  { color: #000 !important; border-bottom-color: #374151 !important; }
        #printClone .afil-fl  { color: #6b7280 !important; }
    }
</style>
@endpush

@section('content')
<section class="atsa-page-hero py-14"
         style="background-image: url('{{ asset('images/historia/movilizacion-atsa-sanidad.jpg') }}'); min-height: 380px;">
    <div class="container-fluid position-relative z-1">
        <div class="col-lg-8">
            <span class="badge bg-white text-primary fs-3 fw-bolder px-3 py-2 mb-4">
                <i class="ti ti-user-plus me-1"></i>AFILIACIÓN
            </span>
            <h1 class="fw-bolder display-5 text-white mb-3">Solicitud de afiliación</h1>
            <p class="fs-5 text-white text-opacity-75 mb-0">
                Completá el formulario online con tu documentación, revisá la vista previa imprimible y presentá tu solicitud con formato institucional.
            </p>
        </div>
    </div>
</section>

<section class="py-10 py-md-14" style="background: #f4f7fb;">
    <div class="container-fluid">

        @if ($errors->any())
            <div class="alert alert-danger rounded-3 mb-6 d-flex align-items-start gap-3">
                <i class="ti ti-alert-circle fs-6 flex-shrink-0 mt-1"></i>
                <div>
                    <strong>Revisá el formulario:</strong> hay datos faltantes o incorrectos. Por favor corregí los campos marcados en rojo.
                </div>
            </div>
        @endif

        <div class="row g-6">
            <div class="col-lg-8">
                <div id="afilacionFormCard">
                    <form id="afiliacionForm" method="POST" action="{{ route('afiliacion.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="card rounded-4 border-0 shadow-sm mb-5">
                            <div class="card-body p-5">
                                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-5">
                                    <div>
                                        <div class="afil-section-label">Vista previa e impresión</div>
                                        <h2 class="afil-section-title">Prepará el formulario antes de enviarlo</h2>
                                    </div>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <button type="button" class="btn btn-outline-primary rounded-3 fw-bold" id="previewAffiliationButton">
                                            <i class="ti ti-eye me-2"></i>Vista previa
                                        </button>
                                        <button type="button" class="btn btn-primary rounded-3 fw-bold" id="printAffiliationButton">
                                            <i class="ti ti-printer me-2"></i>Imprimir formulario
                                        </button>
                                    </div>
                                </div>

                                <div class="rounded-4 p-4 afil-print-note">
                                    <div class="d-flex align-items-start gap-3">
                                        <i class="ti ti-file-text fs-7 text-primary"></i>
                                        <div>
                                            <h3 class="fs-5 fw-bold text-dark mb-2">Membrete institucional ATSA Tucumán</h3>
                                            <p class="fs-3 text-muted mb-0">
                                                La vista previa arma automáticamente una hoja con logo, datos institucionales, declaración y espacios de firma para que puedas imprimirla o guardarla como PDF desde tu navegador.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card rounded-4 border-0 shadow-sm mb-5">
                            <div class="card-body p-5">
                                <div class="afil-section-label">Paso 1</div>
                                <h2 class="afil-section-title mb-5">Datos personales</h2>

                                <div class="row g-4">
                                    <div class="col-md-8">
                                        <label class="form-label fw-semibold">Apellido y nombre <span class="text-danger">*</span></label>
                                        <input type="text" name="apellido_nombre" value="{{ old('apellido_nombre') }}"
                                               class="form-control rounded-3 @error('apellido_nombre') is-invalid @enderror"
                                               placeholder="Ej: García, María Laura" required data-preview="apellido_nombre">
                                        @error('apellido_nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Fecha de nacimiento</label>
                                        <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"
                                               class="form-control rounded-3 @error('fecha_nacimiento') is-invalid @enderror"
                                               data-preview="fecha_nacimiento">
                                        @error('fecha_nacimiento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Nacionalidad</label>
                                        <input type="text" name="nacionalidad" value="{{ old('nacionalidad', 'Argentina') }}"
                                               class="form-control rounded-3" data-preview="nacionalidad">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Estado civil</label>
                                        <select name="estado_civil" class="form-select rounded-3" data-preview="estado_civil">
                                            <option value="">Seleccionar</option>
                                            @foreach (['Soltero/a', 'Casado/a', 'Divorciado/a', 'Viudo/a', 'Unión convivencial'] as $option)
                                                <option value="{{ $option }}" @selected(old('estado_civil') === $option)>{{ $option }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Documento <span class="text-danger">*</span></label>
                                        <select name="tipo_documento" class="form-select rounded-3" required data-preview="tipo_documento">
                                            @foreach (['DNI', 'LC', 'LE', 'Pasaporte'] as $option)
                                                <option value="{{ $option }}" @selected(old('tipo_documento', 'DNI') === $option)>{{ $option }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">N° documento <span class="text-danger">*</span></label>
                                        <input type="text" name="numero_documento" value="{{ old('numero_documento') }}"
                                               class="form-control rounded-3 @error('numero_documento') is-invalid @enderror"
                                               placeholder="Ej: 28456789" required data-preview="numero_documento">
                                        @error('numero_documento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label fw-semibold">Domicilio particular</label>
                                        <input type="text" name="domicilio" value="{{ old('domicilio') }}"
                                               class="form-control rounded-3" placeholder="Calle, número, localidad"
                                               data-preview="domicilio">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Teléfono / WhatsApp <span class="text-danger">*</span></label>
                                        <input type="text" name="telefono" value="{{ old('telefono') }}"
                                               class="form-control rounded-3 @error('telefono') is-invalid @enderror"
                                               placeholder="0381 4xxxxxx" required data-preview="telefono">
                                        @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" value="{{ old('email') }}"
                                               class="form-control rounded-3 @error('email') is-invalid @enderror"
                                               placeholder="tu@email.com" required data-preview="email">
                                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card rounded-4 border-0 shadow-sm mb-5">
                            <div class="card-body p-5">
                                <div class="afil-section-label">Paso 2</div>
                                <h2 class="afil-section-title mb-5">Datos laborales</h2>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Establecimiento</label>
                                        <input type="text" name="establecimiento" value="{{ old('establecimiento') }}"
                                               class="form-control rounded-3"
                                               placeholder="Nombre del hospital, clínica o institución"
                                               data-preview="establecimiento">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Condición en la institución</label>
                                        <input type="text" name="condicion_institucion" value="{{ old('condicion_institucion') }}"
                                               class="form-control rounded-3"
                                               placeholder="Ej: planta permanente, contratado/a, eventual"
                                               data-preview="condicion_institucion">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Nivel</label>
                                        <input type="text" name="nivel" value="{{ old('nivel') }}" class="form-control rounded-3" data-preview="nivel">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Legajo N°</label>
                                        <input type="text" name="legajo" value="{{ old('legajo') }}" class="form-control rounded-3" data-preview="legajo">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Profesión / Cargo</label>
                                        <input type="text" name="profesion" value="{{ old('profesion') }}"
                                               class="form-control rounded-3"
                                               placeholder="Ej: Enfermera, Administrativo, Médico"
                                               data-preview="profesion">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Filial preferida</label>
                                        <select name="filial_preferida" class="form-select rounded-3" data-preview="filial_preferida">
                                            <option value="">Seleccionar</option>
                                            @foreach (['Central Ciudad Deportiva', 'Filial del Sur', 'Filial Este'] as $option)
                                                <option value="{{ $option }}" @selected(old('filial_preferida') === $option)>{{ $option }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Nombre del afiliador</label>
                                        <input type="text" name="nombre_afiliador" value="{{ old('nombre_afiliador') }}"
                                               class="form-control rounded-3" placeholder="Opcional" data-preview="nombre_afiliador">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Celular del afiliador</label>
                                        <input type="text" name="celular_afiliador" value="{{ old('celular_afiliador') }}"
                                               class="form-control rounded-3" placeholder="Opcional" data-preview="celular_afiliador">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card rounded-4 border-0 shadow-sm mb-5">
                            <div class="card-body p-5">
                                <div class="afil-section-label">Paso 3</div>
                                <h2 class="afil-section-title mb-2">Documentación adjunta</h2>
                                <p class="fs-4 text-body mb-5">
                                    Formatos aceptados: PDF, JPG, PNG o WEBP. Máximo 5 MB por archivo y 10 MB para el formulario firmado.
                                </p>

                                <div class="row g-4">
                                    @foreach ([
                                        'dni_frente' => ['Frente del DNI', 'ti-id', true],
                                        'dni_dorso' => ['Dorso del DNI', 'ti-id', true],
                                        'recibo_sueldo' => ['Recibo de sueldo', 'ti-receipt', true],
                                        'formulario_firmado' => ['Formulario firmado escaneado', 'ti-file-upload', false],
                                        'archivo_adicional' => ['Archivo adicional', 'ti-paperclip', false],
                                    ] as $field => [$label, $icon, $required])
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">
                                                {{ $label }}
                                                @if($required) <span class="text-danger">*</span> @endif
                                            </label>
                                            <div class="border-2 border-dashed rounded-3 p-4 bg-white text-center"
                                                 style="border-color: #e2e8f0 !important; cursor: pointer;"
                                                 onclick="this.querySelector('input').click()">
                                                <i class="ti {{ $icon }} fs-7 text-primary d-block mb-2"></i>
                                                <span class="fs-3 fw-semibold text-body d-block mb-1">Seleccionar archivo</span>
                                                <span class="fs-2 text-muted" id="{{ $field }}_name">PDF, JPG o PNG</span>
                                                <input type="file" name="{{ $field }}" id="{{ $field }}"
                                                       class="@error($field) is-invalid @enderror"
                                                       style="display: none;"
                                                       @required($required)
                                                       onchange="document.getElementById('{{ $field }}_name').textContent = this.files[0]?.name || 'PDF, JPG o PNG'">
                                                @error($field) <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Formulario oficial en PDF</label>
                                        @if ($formularioPdf)
                                            <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($formularioPdf->file_path) }}"
                                               target="_blank"
                                               class="d-flex align-items-center gap-3 border-2 border-dashed rounded-3 p-4 text-decoration-none"
                                               style="border-color: #49beff !important; background: #f0faff;">
                                                <i class="ti ti-download fs-6 text-primary flex-shrink-0"></i>
                                                <span>
                                                    <span class="fs-3 fw-bold text-primary d-block">Descargar formulario oficial</span>
                                                    <span class="fs-2 text-muted">Podés imprimirlo, firmarlo y volver a subirlo en esta misma solicitud</span>
                                                </span>
                                            </a>
                                        @else
                                            <div class="border rounded-3 p-4 bg-light text-center text-muted">
                                                <i class="ti ti-clock fs-6 d-block mb-1"></i>
                                                <span class="fs-3">PDF pendiente de carga</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card rounded-4 border-0 shadow-sm">
                            <div class="card-body p-5">
                                <div class="form-check mb-5">
                                    <input class="form-check-input @error('acepta_declaracion') is-invalid @enderror"
                                           type="checkbox" name="acepta_declaracion" value="1"
                                           id="acepta_declaracion" @checked(old('acepta_declaracion')) required>
                                    <label class="form-check-label fs-4" for="acepta_declaracion">
                                        Declaro que los datos son correctos y solicito afiliarme a ATSA Tucumán, autorizando la revisión de la documentación enviada. Entiendo que mi afiliación será confirmada una vez aprobada la documentación.
                                    </label>
                                    @error('acepta_declaracion') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <div class="d-flex gap-3 flex-wrap">
                                    <button type="submit" class="btn btn-primary py-8 px-10 fw-bold rounded-3">
                                        <i class="ti ti-send me-2"></i>Enviar solicitud
                                    </button>
                                    <a href="{{ route('afiliados.index') }}" class="btn btn-outline-secondary py-8 px-9 fw-bold rounded-3">
                                        <i class="ti ti-arrow-left me-2"></i>Volver
                                    </a>
                                </div>

                                <p class="mt-4 mb-0 fs-3 text-muted">
                                    <i class="ti ti-lock me-1"></i>
                                    Tus datos se tratan con total confidencialidad y se utilizan exclusivamente para la gestión administrativa de ATSA Tucumán.
                                </p>
                            </div>
                        </div>
                    </form>
                </div>

                <div id="printPreviewSection" class="mt-6" style="display: none;">
                    <div class="card rounded-4 border-0 shadow-sm afil-preview-shell">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 afil-preview-actions">
                                <div>
                                    <div class="afil-section-label mb-1">Previsualización</div>
                                    <h2 class="afil-section-title">Formulario con membrete listo para imprimir</h2>
                                </div>
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="button" class="btn btn-outline-primary rounded-3 fw-bold" id="refreshPreviewButton">
                                        <i class="ti ti-refresh me-2"></i>Actualizar vista
                                    </button>
                                    <button type="button" class="btn btn-primary rounded-3 fw-bold" id="printPreviewNowButton">
                                        <i class="ti ti-printer me-2"></i>Imprimir
                                    </button>
                                </div>
                            </div>

                            <div class="afil-preview-page">

                                {{-- Marca de agua central --}}
                                <img src="{{ asset('images/logo-atsa.png') }}" alt="" class="afil-wm">

                                {{-- ════ MEMBRETE ÚNICO ════ --}}
                                <div class="afil-hdr">
                                    {{-- Franja azul superior --}}
                                    <div class="afil-hdr-strip">
                                        Asociación de Trabajadores de la Sanidad Argentina
                                        &nbsp;·&nbsp;
                                        <span>Seccional Tucumán</span>
                                    </div>
                                    {{-- Logo + datos institucionales --}}
                                    <div class="afil-hdr-main">
                                        <img src="{{ asset('images/logo-atsa.png') }}" alt="ATSA Tucumán" class="afil-hdr-logo">
                                        <div>
                                            <div class="afil-hdr-name">ATSA Tucumán</div>
                                            <div class="afil-hdr-leg">Personería Gremial N° 000394 &nbsp;·&nbsp; Adherida a F.A.T.S.A y C.G.T.</div>
                                            <div class="afil-hdr-contact">Paraguay y Thames, San Miguel de Tucumán &nbsp;·&nbsp; Tel: 0381 433-1665 &nbsp;·&nbsp; www.atsatucuman.org.ar</div>
                                        </div>
                                    </div>
                                    {{-- Línea de color al pie del header --}}
                                    <div class="afil-hdr-border"></div>
                                </div>

                                {{-- ════ TÍTULO + FECHA ════ --}}
                                <div class="afil-title-bar">
                                    <span class="afil-title-bar-name">Solicitud de Adhesión Sindical</span>
                                    <span class="afil-title-bar-date">
                                        San Miguel de Tucumán, <span id="previewFechaLugar"></span>
                                    </span>
                                </div>

                                {{-- ════ CUERPO DEL FORMULARIO ════ --}}
                                <div class="afil-body">

                                    {{-- Sección: Datos personales --}}
                                    <div class="afil-sec-lbl">Datos personales del solicitante</div>

                                    {{-- Apellido y nombre | Fecha de nacimiento --}}
                                    <div class="afil-fg c21">
                                        <div class="afil-f">
                                            <span class="afil-fl">Apellido y Nombre</span>
                                            <span class="afil-fv" data-preview-target="apellido_nombre"></span>
                                        </div>
                                        <div class="afil-f">
                                            <span class="afil-fl">Fecha de Nacimiento</span>
                                            <span class="afil-fv" data-preview-target="fecha_nacimiento"></span>
                                        </div>
                                    </div>

                                    {{-- Nacionalidad | Estado civil | Tipo doc. | N° documento --}}
                                    <div class="afil-fg c4">
                                        <div class="afil-f">
                                            <span class="afil-fl">Nacionalidad</span>
                                            <span class="afil-fv" data-preview-target="nacionalidad"></span>
                                        </div>
                                        <div class="afil-f">
                                            <span class="afil-fl">Estado Civil</span>
                                            <span class="afil-fv" data-preview-target="estado_civil"></span>
                                        </div>
                                        <div class="afil-f">
                                            <span class="afil-fl">Tipo Doc.</span>
                                            <span class="afil-fv" data-preview-target="tipo_documento"></span>
                                        </div>
                                        <div class="afil-f">
                                            <span class="afil-fl">N° de Documento</span>
                                            <span class="afil-fv" data-preview-target="numero_documento"></span>
                                        </div>
                                    </div>

                                    {{-- Domicilio | Teléfono | Correo electrónico --}}
                                    <div class="afil-fg c212">
                                        <div class="afil-f">
                                            <span class="afil-fl">Domicilio Particular</span>
                                            <span class="afil-fv" data-preview-target="domicilio"></span>
                                        </div>
                                        <div class="afil-f">
                                            <span class="afil-fl">Teléfono</span>
                                            <span class="afil-fv" data-preview-target="telefono"></span>
                                        </div>
                                        <div class="afil-f">
                                            <span class="afil-fl">Correo Electrónico</span>
                                            <span class="afil-fv" data-preview-target="email"></span>
                                        </div>
                                    </div>

                                    {{-- Sección: Datos laborales --}}
                                    <div class="afil-sec-lbl mt">Datos laborales</div>

                                    {{-- Establecimiento | Condición | Filial --}}
                                    <div class="afil-fg c211">
                                        <div class="afil-f">
                                            <span class="afil-fl">Establecimiento al que Pertenece</span>
                                            <span class="afil-fv" data-preview-target="establecimiento"></span>
                                        </div>
                                        <div class="afil-f">
                                            <span class="afil-fl">Condición en Institución</span>
                                            <span class="afil-fv" data-preview-target="condicion_institucion"></span>
                                        </div>
                                        <div class="afil-f">
                                            <span class="afil-fl">Filial Preferida</span>
                                            <span class="afil-fv" data-preview-target="filial_preferida"></span>
                                        </div>
                                    </div>

                                    {{-- Profesión | Nivel | Legajo --}}
                                    <div class="afil-fg c3">
                                        <div class="afil-f">
                                            <span class="afil-fl">Profesión / Categoría</span>
                                            <span class="afil-fv" data-preview-target="profesion"></span>
                                        </div>
                                        <div class="afil-f">
                                            <span class="afil-fl">Nivel</span>
                                            <span class="afil-fv" data-preview-target="nivel"></span>
                                        </div>
                                        <div class="afil-f">
                                            <span class="afil-fl">Legajo N°</span>
                                            <span class="afil-fv" data-preview-target="legajo"></span>
                                        </div>
                                    </div>

                                    {{-- Declaración jurada / autorización de descuento --}}
                                    <div class="afil-auth">
                                        El/La que suscribe tiene el agrado de dirigirse a Ud. a los efectos de solicitar su incorporación como afiliado/a a la Asociación de Trabajadores de la Sanidad Argentina — Seccional Tucumán (ATSA) y de <strong>autorizar expresamente el descuento del 2,5&nbsp;% de su remuneración mensual</strong>, destinado a cuota sindical ordinaria, de conformidad con lo establecido en la Ley N°&nbsp;23.551 de Asociaciones Sindicales y la normativa laboral vigente.
                                    </div>

                                    {{-- Sección de firmas --}}
                                    <div class="afil-sigs">
                                        <div class="afil-sigs-ttl">Firmas requeridas</div>

                                        {{-- Firma del afiliado/a — ancho completo --}}
                                        <div class="afil-sigs-row c1">
                                            <div class="afil-sig">
                                                <div class="afil-sig-sp tall"></div>
                                                <div class="afil-sig-cap">Firma y Aclaración del Afiliado / a</div>
                                                <div class="afil-sig-sub">Aclaración de firma · D.N.I.</div>
                                            </div>
                                        </div>

                                        {{-- Afiliador: nombre | firma | celular --}}
                                        <div class="afil-sigs-row c3">
                                            <div class="afil-sig">
                                                <div class="afil-sig-sp"></div>
                                                <div class="afil-sig-cap" data-preview-target="nombre_afiliador">Nombre del Afiliador</div>
                                                <div class="afil-sig-sub">Nombre y apellido</div>
                                            </div>
                                            <div class="afil-sig">
                                                <div class="afil-sig-sp"></div>
                                                <div class="afil-sig-cap">Firma del Afiliador</div>
                                                <div class="afil-sig-sub">Firma y sello</div>
                                            </div>
                                            <div class="afil-sig">
                                                <div class="afil-sig-sp"></div>
                                                <div class="afil-sig-cap" data-preview-target="celular_afiliador">Celular del Afiliador</div>
                                                <div class="afil-sig-sub">Teléfono de contacto</div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Banda de uso oficial --}}
                                    <div class="afil-oficial-band" style="margin-top: 4mm;">
                                        <div class="afil-oficial-f">
                                            <span class="afil-oficial-lbl">N° de afiliado (uso ATSA)</span>
                                            <span class="afil-oficial-val"></span>
                                        </div>
                                        <div class="afil-oficial-f">
                                            <span class="afil-oficial-lbl">Fecha de alta</span>
                                            <span class="afil-oficial-val"></span>
                                        </div>
                                        <div class="afil-oficial-f">
                                            <span class="afil-oficial-lbl">Firma responsable ATSA</span>
                                            <span class="afil-oficial-val"></span>
                                        </div>
                                    </div>

                                    {{-- Pie del documento --}}
                                    <div class="afil-footer-doc">
                                        <span>ATSA Tucumán · Formulario de Adhesión Sindical · Uso exclusivo administrativo</span>
                                        <span>Paraguay y Thames, S.M. de Tucumán · 0381 433-1665</span>
                                    </div>

                                </div>{{-- /afil-body --}}
                            </div>{{-- /afil-preview-page --}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card rounded-4 border-0 shadow-sm mb-5">
                    <div class="card-body p-5">
                        <h3 class="fw-bolder fs-6 mb-4">
                            <i class="ti ti-route me-2 text-primary"></i>Cómo funciona
                        </h3>
                        <div class="afil-step">
                            <div class="afil-step-num">1</div>
                            <div>
                                <p class="afil-step-title">Completás el formulario</p>
                                <p class="afil-step-desc">Ingresás tus datos personales, laborales y adjuntás DNI más recibo de sueldo.</p>
                            </div>
                        </div>
                        <div class="afil-step">
                            <div class="afil-step-num">2</div>
                            <div>
                                <p class="afil-step-title">ATSA revisa tu solicitud</p>
                                <p class="afil-step-desc">El equipo administrativo valida tu documentación en un plazo estimado de 2 a 5 días hábiles.</p>
                            </div>
                        </div>
                        <div class="afil-step">
                            <div class="afil-step-num">3</div>
                            <div>
                                <p class="afil-step-title">Se aprueba tu alta</p>
                                <p class="afil-step-desc">Recibís un correo de confirmación con tu número de afiliado y acceso al portal.</p>
                            </div>
                        </div>
                        <div class="afil-step">
                            <div class="afil-step-num">4</div>
                            <div>
                                <p class="afil-step-title">Accedés a todos los beneficios</p>
                                <p class="afil-step-desc">Carnet digital, descuentos, turismo, formación y respaldo gremial desde el primer día.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card rounded-4 border-0 shadow-sm mb-5">
                    <div class="card-body p-5">
                        <h3 class="fw-bolder fs-6 mb-4">
                            <i class="ti ti-gift me-2 text-primary"></i>¿Qué obtenés al afiliarte?
                        </h3>
                        <div class="afil-benefit">
                            <div class="afil-benefit-icon"><i class="ti ti-gavel"></i></div>
                            <p class="afil-benefit-text">Representación gremial y defensa ante conflictos laborales</p>
                        </div>
                        <div class="afil-benefit">
                            <div class="afil-benefit-icon"><i class="ti ti-beach"></i></div>
                            <p class="afil-benefit-text">Turismo y recreación con hoteles y convenios exclusivos</p>
                        </div>
                        <div class="afil-benefit">
                            <div class="afil-benefit-icon"><i class="ti ti-school"></i></div>
                            <p class="afil-benefit-text">Formación en el CENT N°74 y cursos de actualización</p>
                        </div>
                        <div class="afil-benefit">
                            <div class="afil-benefit-icon"><i class="ti ti-heart-handshake"></i></div>
                            <p class="afil-benefit-text">Acción social con subsidios, asistencia y acompañamiento</p>
                        </div>
                        <div class="afil-benefit">
                            <div class="afil-benefit-icon"><i class="ti ti-id-badge"></i></div>
                            <p class="afil-benefit-text">Carnet digital de afiliado con código QR verificable</p>
                        </div>
                        <div class="afil-benefit">
                            <div class="afil-benefit-icon"><i class="ti ti-chart-bar"></i></div>
                            <p class="afil-benefit-text">Escalas salariales actualizadas y negociación paritaria</p>
                        </div>
                        <div class="afil-benefit">
                            <div class="afil-benefit-icon"><i class="ti ti-map-pin"></i></div>
                            <p class="afil-benefit-text">Atención en filiales Central, Sur y Este</p>
                        </div>
                    </div>
                </div>

                <div class="afil-testimonial mb-5">
                    <p class="afil-testimonial-quote">
                        "Afiliarme a ATSA fue una decisión que me dio respaldo y cercanía. Cuando necesité ayuda laboral y asesoramiento, el sindicato respondió rápido y con mucho compromiso."
                    </p>
                    <div class="afil-testimonial-author">
                        <div class="afil-testimonial-avatar">MR</div>
                        <div>
                            <p class="afil-testimonial-name">María R.</p>
                            <p class="afil-testimonial-role">Enfermera - Hospital Centro de Salud</p>
                        </div>
                    </div>
                </div>

                <div class="card rounded-4 border-0 shadow-sm" style="background: #ecf2ff;">
                    <div class="card-body p-4">
                        <h4 class="fw-bolder fs-5 mb-3" style="color: #1e3a5f;">¿Tenés dudas?</h4>
                        <p class="fs-3 mb-4" style="color: #475569;">
                            Nuestro equipo está disponible para ayudarte de lunes a viernes de 8 a 16 hs.
                        </p>
                        <a href="https://wa.me/543814331665" target="_blank" rel="noopener"
                           class="btn btn-success w-100 fw-bold rounded-3 mb-2">
                            <i class="ti ti-brand-whatsapp me-2"></i>WhatsApp ATSA
                        </a>
                        <a href="{{ route('contacto.index') }}" class="btn w-100 fw-bold rounded-3"
                           style="background: #1e3a5f; color: #fff; border: none;">
                            <i class="ti ti-mail me-2"></i>Escribirnos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    (() => {
        const form = document.getElementById('afiliacionForm');
        const previewSection = document.getElementById('printPreviewSection');
        const previewButton = document.getElementById('previewAffiliationButton');
        const printButton = document.getElementById('printAffiliationButton');
        const printPreviewNowButton = document.getElementById('printPreviewNowButton');
        const refreshPreviewButton = document.getElementById('refreshPreviewButton');

        if (!form || !previewSection) {
            return;
        }

        const placeholder = 'Pendiente de completar';

        const formatDate = (value) => {
            if (!value) {
                return '';
            }

            const parts = value.split('-');
            if (parts.length !== 3) {
                return value;
            }

            return `${parts[2]}/${parts[1]}/${parts[0]}`;
        };

        const writeValue = (key, value) => {
            document.querySelectorAll(`[data-preview-target="${key}"]`).forEach((element) => {
                const finalValue = value && String(value).trim() !== '' ? value : placeholder;
                element.textContent = finalValue;
                element.classList.toggle('is-placeholder', finalValue === placeholder);
            });
        };

        const updatePreview = () => {
            const formData = new FormData(form);

            writeValue('apellido_nombre', formData.get('apellido_nombre'));
            writeValue('fecha_nacimiento', formatDate(formData.get('fecha_nacimiento')));
            writeValue('nacionalidad', formData.get('nacionalidad'));
            writeValue('estado_civil', formData.get('estado_civil'));
            writeValue('tipo_documento', formData.get('tipo_documento'));
            writeValue('numero_documento', formData.get('numero_documento'));
            writeValue('domicilio', formData.get('domicilio'));
            writeValue('telefono', formData.get('telefono'));
            writeValue('email', formData.get('email'));
            writeValue('establecimiento', formData.get('establecimiento'));
            writeValue('condicion_institucion', formData.get('condicion_institucion'));
            writeValue('nivel', formData.get('nivel'));
            writeValue('legajo', formData.get('legajo'));
            writeValue('profesion', formData.get('profesion'));
            writeValue('filial_preferida', formData.get('filial_preferida'));
            writeValue('nombre_afiliador', formData.get('nombre_afiliador'));
            writeValue('celular_afiliador', formData.get('celular_afiliador'));

            const now = new Date();
            const currentDate = now.toLocaleDateString('es-AR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
            });
            const previewFechaLugar = document.getElementById('previewFechaLugar');
            if (previewFechaLugar) {
                previewFechaLugar.textContent = currentDate;
                previewFechaLugar.classList.toggle('is-placeholder', false);
            }

            previewSection.style.display = 'block';
        };

        previewButton?.addEventListener('click', () => {
            updatePreview();
            previewSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });

        refreshPreviewButton?.addEventListener('click', updatePreview);

        const runPrint = () => {
            updatePreview();
            const page = previewSection.querySelector('.afil-preview-page');
            if (!page) return;

            // Eliminar clon anterior si existe
            const oldClone = document.getElementById('printClone');
            if (oldClone) oldClone.remove();

            // Clonar la página al body (hijo directo) para que CSS print la aisole fácilmente
            const clone = page.cloneNode(true);
            clone.id = 'printClone';
            clone.style.display = 'none';
            document.body.appendChild(clone);

            setTimeout(() => {
                window.print();
                setTimeout(() => {
                    const c = document.getElementById('printClone');
                    if (c) c.remove();
                }, 500);
            }, 150);
        };

        printButton?.addEventListener('click', runPrint);
        printPreviewNowButton?.addEventListener('click', runPrint);

        form.querySelectorAll('[data-preview]').forEach((input) => {
            input.addEventListener('input', updatePreview);
            input.addEventListener('change', updatePreview);
        });

        @if(old())
        updatePreview();
        @endif
    })();
</script>
@endpush
