@extends('layouts.app')

@section('title', 'Solicitud enviada exitosamente | ATSA Tucumán')

@push('styles')
<style>
    .gracias-hero {
        background: linear-gradient(135deg, #1e3a5f 0%, #163050 60%, #0f2236 100%);
        padding: 64px 0;
        position: relative;
        overflow: hidden;
    }
    .gracias-hero::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 320px; height: 320px;
        border-radius: 50%;
        background: rgba(73, 190, 255, .10);
    }
    .gracias-hero::after {
        content: '';
        position: absolute;
        bottom: -80px; left: -40px;
        width: 240px; height: 240px;
        border-radius: 50%;
        background: rgba(255,255,255,.04);
    }
    .gracias-check {
        width: 90px; height: 90px;
        border-radius: 50%;
        background: rgba(19, 222, 185, .15);
        border: 3px solid #13deb9;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 28px;
        font-size: 42px;
        color: #13deb9;
        animation: popIn .5s ease;
    }
    @keyframes popIn {
        0%   { transform: scale(0); opacity: 0; }
        70%  { transform: scale(1.12); }
        100% { transform: scale(1); opacity: 1; }
    }
    .gracias-num-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: rgba(73, 190, 255, .15);
        border: 1px solid rgba(73, 190, 255, .3);
        border-radius: 50px;
        padding: 10px 22px;
        color: #49beff;
        font-size: 15px;
        font-weight: 700;
        margin-top: 20px;
    }

    .gracias-step {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 16px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .gracias-step:last-child { border-bottom: none; }
    .gracias-step-num {
        width: 36px; height: 36px;
        border-radius: 10px;
        background: #1e3a5f;
        color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 15px; font-weight: 800;
        font-family: 'Outfit', sans-serif;
        flex-shrink: 0;
    }
    .gracias-step-num.done {
        background: #13deb9;
    }
    .gracias-step-title { font-size: 14px; font-weight: 700; color: #1e293b; margin-bottom: 3px; }
    .gracias-step-desc  { font-size: 13px; color: #64748b; line-height: 1.5; margin: 0; }

    .gracias-info-pill {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        border-radius: 12px;
        background: #f8fbff;
        border: 1px solid #e2e8f0;
        font-size: 14px;
        color: #2a3547;
    }
    .gracias-info-pill i {
        font-size: 20px;
        color: #1e3a5f;
        flex-shrink: 0;
    }
    .gracias-info-pill strong { display: block; font-weight: 700; margin-bottom: 1px; }
    .gracias-info-pill span  { color: #64748b; font-size: 13px; }
</style>
@endpush

@section('content')

{{-- ══════════ HERO CONFIRMACIÓN ══════════ --}}
<section class="gracias-hero">
    <div class="container-fluid position-relative z-1 text-center">
        <div class="gracias-check">
            <i class="ti ti-check"></i>
        </div>
        <span class="badge mb-4 px-3 py-2 fw-bold" style="background: rgba(255,255,255,.12); color: #d9ecff; font-size: 12px; letter-spacing: .1em;">
            SOLICITUD RECIBIDA
        </span>
        <h1 class="fw-bolder text-white mb-3" style="font-size: clamp(1.8rem, 4vw, 2.8rem); font-family: 'Outfit', sans-serif;">
            ¡Tu solicitud fue enviada con éxito!
        </h1>
        <p class="mb-0 mx-auto" style="max-width: 560px; color: rgba(255,255,255,.72); font-size: 16px; line-height: 1.65;">
            El equipo de ATSA Tucumán revisará tus datos y la documentación adjunta.
            Te contactaremos por teléfono o email cuando el proceso esté completo.
        </p>
        @if (session('solicitud_id'))
            <div class="gracias-num-badge mx-auto mt-4 d-inline-flex">
                <i class="ti ti-hash"></i>
                Número de solicitud: <strong>#{{ str_pad((string) session('solicitud_id'), 6, '0', STR_PAD_LEFT) }}</strong>
            </div>
        @endif
    </div>
</section>

{{-- ══════════ CONTENIDO PRINCIPAL ══════════ --}}
<section class="py-10 py-lg-14" style="background: #f4f7fb;">
    <div class="container-fluid">
        <div class="row g-6 justify-content-center">

            {{-- ── Col principal ── --}}
            <div class="col-lg-7">

                {{-- Próximos pasos --}}
                <div class="card rounded-4 border-0 shadow-sm mb-5">
                    <div class="card-body p-5">
                        <h2 class="fw-bolder fs-6 mb-5">
                            <i class="ti ti-route me-2 text-primary"></i>¿Qué pasa ahora?
                        </h2>
                        <div class="gracias-step">
                            <div class="gracias-step-num done"><i class="ti ti-check"></i></div>
                            <div>
                                <p class="gracias-step-title">✓ Solicitud recibida</p>
                                <p class="gracias-step-desc">Recibimos tu formulario y documentación adjunta correctamente. Ya está en cola de revisión.</p>
                            </div>
                        </div>
                        <div class="gracias-step">
                            <div class="gracias-step-num">2</div>
                            <div>
                                <p class="gracias-step-title">Revisión de documentación</p>
                                <p class="gracias-step-desc">El equipo administrativo de ATSA revisará tu DNI, recibo de sueldo y datos declarados en un plazo de 2 a 5 días hábiles.</p>
                            </div>
                        </div>
                        <div class="gracias-step">
                            <div class="gracias-step-num">3</div>
                            <div>
                                <p class="gracias-step-title">Confirmación de alta</p>
                                <p class="gracias-step-desc">Si la documentación está completa y correcta, recibirás un email con tu número de afiliado y contraseña para el portal privado.</p>
                            </div>
                        </div>
                        <div class="gracias-step">
                            <div class="gracias-step-num">4</div>
                            <div>
                                <p class="gracias-step-title">¡Ya sos parte de ATSA!</p>
                                <p class="gracias-step-desc">Accedés a tu carnet digital, beneficios, turismo y todo el respaldo gremial desde tu primer día como afiliado.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Info de contacto --}}
                <div class="card rounded-4 border-0 shadow-sm mb-5">
                    <div class="card-body p-5">
                        <h3 class="fw-bolder fs-6 mb-4">
                            <i class="ti ti-info-circle me-2 text-primary"></i>Información importante
                        </h3>
                        <div class="d-flex flex-column gap-3">
                            <div class="gracias-info-pill">
                                <i class="ti ti-clock"></i>
                                <div>
                                    <strong>Tiempo de respuesta</strong>
                                    <span>2 a 5 días hábiles para la revisión inicial</span>
                                </div>
                            </div>
                            <div class="gracias-info-pill">
                                <i class="ti ti-mail"></i>
                                <div>
                                    <strong>Comunicación por email</strong>
                                    <span>Revisá tu bandeja de entrada y carpeta de spam para los mensajes de ATSA</span>
                                </div>
                            </div>
                            <div class="gracias-info-pill">
                                <i class="ti ti-file-alert"></i>
                                <div>
                                    <strong>Si falta documentación</strong>
                                    <span>Te contactaremos para completar lo que sea necesario antes del alta</span>
                                </div>
                            </div>
                            <div class="gracias-info-pill">
                                <i class="ti ti-phone"></i>
                                <div>
                                    <strong>¿Tenés dudas?</strong>
                                    <span>Llamá al 0381 4331665 de lunes a viernes de 8:00 a 16:00 hs</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('home') }}" class="btn btn-primary py-8 px-10 fw-bold rounded-3">
                        <i class="ti ti-home-2 me-2"></i>Ir al inicio
                    </a>
                    <a href="{{ route('novedades.index') }}" class="btn btn-outline-primary py-8 px-9 fw-bold rounded-3">
                        <i class="ti ti-newspaper me-2"></i>Ver novedades
                    </a>
                    <a href="{{ route('contacto.index') }}" class="btn btn-outline-secondary py-8 px-9 fw-bold rounded-3">
                        <i class="ti ti-mail me-2"></i>Contacto
                    </a>
                    @if (session('solicitud_pdf_token'))
                        <a href="{{ route('afiliacion.pdf', session('solicitud_pdf_token')) }}" class="btn btn-outline-danger py-8 px-9 fw-bold rounded-3" target="_blank">
                            <i class="ti ti-file-download me-2"></i>Descargar solicitud PDF
                        </a>
                    @endif
                </div>
            </div>

            {{-- ── Sidebar ── --}}
            <div class="col-lg-4">

                {{-- Beneficios que se vienen --}}
                <div class="card rounded-4 border-0 shadow-sm mb-5">
                    <div class="card-body p-5">
                        <h3 class="fw-bolder fs-6 mb-4">
                            <i class="ti ti-gift me-2 text-primary"></i>Muy pronto tendrás acceso a
                        </h3>
                        <div class="d-flex flex-column gap-3">
                            @foreach ([
                                ['ti-id-badge', 'Carnet digital con código QR verificable'],
                                ['ti-gavel', 'Representación y asesoramiento gremial'],
                                ['ti-beach', 'Turismo: Ciudad Deportiva y Hotel ATSA'],
                                ['ti-school', 'Formación gratuita en CENT N°74'],
                                ['ti-heart-handshake', 'Acción social: subsidios y beneficios'],
                                ['ti-chart-bar', 'Escalas salariales actualizadas'],
                            ] as [$icon, $label])
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width:34px;height:34px;border-radius:9px;background:#ecf2ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="ti {{ $icon }}" style="color:#1e3a5f;font-size:17px;"></i>
                                    </div>
                                    <span style="font-size:13px;font-weight:600;color:#2a3547;">{{ $label }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- CTA Turismo --}}
                <div class="rounded-4 overflow-hidden position-relative mb-5"
                     style="background: linear-gradient(135deg,#1e3a5f,#0f2236); min-height: 180px;">
                    <div class="p-5 position-relative z-1">
                        <p class="fw-bold mb-2" style="font-size:11px;letter-spacing:.1em;color:#49beff;text-transform:uppercase;">MIENTRAS ESPERÁS</p>
                        <h4 class="fw-bolder text-white mb-3" style="font-family:'Outfit',sans-serif;">Conocé los beneficios turísticos</h4>
                        <p class="mb-4" style="color:rgba(255,255,255,.7);font-size:13px;line-height:1.6;">Ciudad Deportiva, Hotel ATSA Termas y convenios FATSA en todo el país.</p>
                        <a href="{{ route('turismo.index') }}" class="btn btn-light btn-sm fw-bold px-4 py-6" style="color:#1e3a5f;font-size:13px;">
                            <i class="ti ti-beach me-2"></i>Ver turismo
                        </a>
                    </div>
                </div>

                {{-- WhatsApp --}}
                <a href="https://wa.me/543814331665" target="_blank" rel="noopener"
                   class="btn btn-success w-100 fw-bold py-8 rounded-3">
                    <i class="ti ti-brand-whatsapp me-2"></i>Consultar por WhatsApp
                </a>

            </div>

        </div>
    </div>
</section>

@endsection
