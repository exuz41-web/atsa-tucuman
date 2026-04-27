@extends('layouts.app')

@section('title', 'Contacto | ATSA Tucumán')

@section('meta_description', 'Contactanos por teléfono, WhatsApp o formulario web. Sede central en Paraguay y Thames, San Miguel de Tucumán. Lunes a viernes de 8:00 a 16:00 hs.')

@section('content')
    @php
        $siteSetting = \App\Models\SiteSetting::current();
        $facebookUrl       = $siteSetting->facebook_url  ?: 'https://www.facebook.com/ATSATucuman';
        $whatsappUrl       = 'https://wa.me/' . ($siteSetting->whatsapp ?: '543814331665');
        $telefonoPrincipal = $siteSetting->phone     ?: '0381 4331665';
        $direccionCentral  = $siteSetting->address   ?: 'Paraguay y Thames, San Miguel de Tucumán';
        $horarios          = $siteSetting->schedule  ?: 'Lunes a viernes de 8:00 a 16:00 hs';
    @endphp

    <section class="atsa-page-hero py-12 py-lg-15 position-relative" style="background-image: url('{{ asset('images/historia/ciudad-deportiva-atsa.jpg') }}'); min-height: 360px;">
        <div class="container-fluid position-relative z-1">
            <div class="row justify-content-center text-center">
                <div class="col-xl-8 col-lg-9">
                    <span class="section-badge bg-white text-primary shadow-sm">
                        <i class="ti ti-headset"></i>
                        CONTACTO
                    </span>
                    <h1 class="display-4 fw-bolder text-white mb-4">Estamos para acompañarte</h1>
                    <p class="fs-5 text-white mx-auto mb-5" style="max-width: 760px; color: rgba(255,255,255,0.84) !important;">
                        Consultas gremiales, afiliación, asesoramiento y formación. Elegí el canal que te resulte más cómodo
                        y nos comunicamos con vos a la brevedad.
                    </p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <span class="contact-hero-pill">
                            <i class="ti ti-phone"></i>
                            {{ $telefonoPrincipal }}
                        </span>
                        <span class="contact-hero-pill">
                            <i class="ti ti-clock-hour-4"></i>
                            {{ $horarios }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-10 py-lg-15 contact-page-section">
        <div class="container-fluid">
            <div class="row g-5 align-items-start">
                <div class="col-lg-5">
                    <div class="contact-sidebar-card mb-4">
                        <div class="d-flex align-items-start gap-3 mb-4">
                            <div class="contact-sidebar-icon">
                                <i class="ti ti-building-community"></i>
                            </div>
                            <div>
                                <p class="text-primary fw-semibold mb-1 fs-3">ATENCIÓN INSTITUCIONAL</p>
                                <h2 class="fw-bolder mb-2">Sede central</h2>
                                <p class="text-muted mb-0">{{ $direccionCentral }}</p>
                            </div>
                        </div>

                        <div class="contact-info-list">
                            <div class="contact-info-row">
                                <div class="contact-info-icon"><i class="ti ti-map-pin"></i></div>
                                <div>
                                    <p class="fw-semibold mb-1">Dirección</p>
                                    <p class="text-muted mb-0">{{ $direccionCentral }}</p>
                                </div>
                            </div>
                            <div class="contact-info-row">
                                <div class="contact-info-icon"><i class="ti ti-phone-call"></i></div>
                                <div>
                                    <p class="fw-semibold mb-1">Teléfono</p>
                                    <p class="text-muted mb-0">{{ $telefonoPrincipal }}</p>
                                </div>
                            </div>
                            <div class="contact-info-row">
                                <div class="contact-info-icon"><i class="ti ti-clock-hour-4"></i></div>
                                <div>
                                    <p class="fw-semibold mb-1">Horarios</p>
                                    <p class="text-muted mb-0">{{ $horarios }}</p>
                                </div>
                            </div>
                            <div class="contact-info-row">
                                <div class="contact-info-icon"><i class="ti ti-brand-facebook"></i></div>
                                <div>
                                    <p class="fw-semibold mb-1">Facebook oficial</p>
                                    <a href="{{ $facebookUrl }}" target="_blank" rel="noopener" class="text-primary fw-semibold text-decoration-none">
                                        ATSA Tucumán
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-3 mt-5">
                            <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="btn btn-success py-8 fw-bold">
                                <i class="ti ti-brand-whatsapp me-2"></i>Escribir por WhatsApp
                            </a>
                            <a href="{{ $facebookUrl }}" target="_blank" rel="noopener" class="btn btn-outline-atsa py-8 fw-bold">
                                <i class="ti ti-brand-facebook me-2"></i>Ver Facebook oficial
                            </a>
                        </div>
                    </div>

                    <div class="contact-branches-card">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="contact-sidebar-icon contact-sidebar-icon--soft">
                                <i class="ti ti-map-2"></i>
                            </div>
                            <div>
                                <p class="text-primary fw-semibold mb-1 fs-3">PRESENCIA TERRITORIAL</p>
                                <h3 class="fw-bolder mb-0">También podés acercarte a</h3>
                            </div>
                        </div>

                        <div class="contact-branch-grid">
                            @foreach ([
                                ['Central Ciudad Deportiva', 'Paraguay y Thames, San Miguel de Tucumán'],
                                ['Filial Este', 'Cam. del Carmen 90, Banda del Río Salí · 381 5677170'],
                                ['Filial del Sur', 'Julio Argentino Roca 371, Concepción'],
                            ] as $filial)
                                <div class="contact-branch-item">
                                    <i class="ti ti-map-pin text-primary"></i>
                                    <div>
                                        <p class="fw-semibold mb-1">{{ $filial[0] }}</p>
                                        <p class="text-muted mb-0">{{ $filial[1] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="contact-form-card">
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4 mb-6">
                            <div>
                                <p class="text-primary fw-semibold mb-2 fs-3">FORMULARIO WEB</p>
                                <h2 class="fw-bolder mb-2">Enviá tu consulta</h2>
                                <p class="text-muted mb-0">
                                    Completá los datos y nuestro equipo se va a comunicar con vos por el medio indicado.
                                </p>
                            </div>
                            <div class="contact-form-note">
                                <i class="ti ti-shield-check"></i>
                                <span>Tus datos se usan solo para responder tu consulta.</span>
                            </div>
                        </div>

                        <div id="contactSuccess" class="alert alert-success d-none align-items-center gap-3 mb-5 rounded-3 p-4" role="alert">
                            <i class="ti ti-circle-check fs-5 text-success"></i>
                            <span id="contactSuccessMsg"></span>
                        </div>
                        <div id="contactError" class="alert alert-danger d-none align-items-center gap-3 mb-5 rounded-3 p-4" role="alert">
                            <i class="ti ti-alert-circle fs-5 text-danger"></i>
                            <span id="contactErrorMsg"></span>
                        </div>

                        <form id="contactForm" novalidate>
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nombre completo <span class="text-danger">*</span></label>
                                    <input type="text" name="nombre" class="form-control form-control-lg contact-input" placeholder="Tu nombre y apellido" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control form-control-lg contact-input" placeholder="tu@email.com" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Teléfono</label>
                                    <input type="tel" name="telefono" class="form-control form-control-lg contact-input" placeholder="0381 0000000">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Asunto <span class="text-danger">*</span></label>
                                    <select name="asunto" class="form-select form-select-lg contact-input" required>
                                        <option value="">Seleccionar asunto</option>
                                        <option value="consulta">Consulta general</option>
                                        <option value="afiliacion">Afiliación</option>
                                        <option value="asesoramiento">Asesoramiento</option>
                                        <option value="formacion">Formación</option>
                                        <option value="otro">Otro</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Mensaje <span class="text-danger">*</span></label>
                                    <textarea name="mensaje" class="form-control contact-input py-5" rows="7" placeholder="Contanos en qué podemos ayudarte" required></textarea>
                                </div>
                            </div>

                            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-5 pt-2">
                                <p class="text-muted mb-0 fs-4">
                                    Si preferís atención inmediata, también podés escribirnos por WhatsApp.
                                </p>
                                <button type="submit" id="contactSubmitBtn" class="btn btn-atsa py-8 px-10 fw-bold">
                                    <span id="contactBtnText"><i class="ti ti-send me-2"></i>Enviar mensaje</span>
                                    <span id="contactBtnSpinner" class="d-none">
                                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>Enviando...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pb-10 pb-lg-15">
        <div class="container-fluid">
            <div class="contact-map-card overflow-hidden">
                <div class="row g-0">
                    <div class="col-lg-4">
                        <div class="contact-map-copy h-100">
                            <p class="text-primary fw-semibold mb-2 fs-3">UBICACIÓN</p>
                            <h2 class="fw-bolder mb-3">Encontranos en la sede central</h2>
                            <p class="text-muted mb-4">
                                La atención institucional y gremial se concentra en Ciudad Deportiva, con acceso desde Paraguay y Thames,
                                San Miguel de Tucumán.
                            </p>
                            <div class="contact-map-meta">
                                <span><i class="ti ti-map-pin me-2"></i>{{ $direccionCentral }}</span>
                                <span><i class="ti ti-phone me-2"></i>{{ $telefonoPrincipal }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <iframe
                            src="https://www.google.com/maps?q=Paraguay%20y%20Thames%2C%20San%20Miguel%20de%20Tucum%C3%A1n&output=embed"
                            width="100%"
                            height="460"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('contactForm');
    var btn = document.getElementById('contactSubmitBtn');
    var btnText = document.getElementById('contactBtnText');
    var btnSpin = document.getElementById('contactBtnSpinner');
    var okBox = document.getElementById('contactSuccess');
    var errBox = document.getElementById('contactError');

    if (!form) return;

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        btn.disabled = true;
        btnText.classList.add('d-none');
        btnSpin.classList.remove('d-none');
        okBox.classList.add('d-none');
        errBox.classList.add('d-none');

        var asuntoMap = {
            consulta: 'Consulta general',
            afiliacion: 'Afiliación',
            asesoramiento: 'Asesoramiento',
            formacion: 'Formación',
            otro: 'Otro'
        };

        var asuntoVal = form.querySelector('select[name="asunto"]')?.value || '';
        var payload = {
            nombre: form.querySelector('[name="nombre"]')?.value,
            email: form.querySelector('[name="email"]')?.value,
            telefono: form.querySelector('[name="telefono"]')?.value,
            asunto: asuntoMap[asuntoVal] || asuntoVal,
            mensaje: form.querySelector('[name="mensaje"]')?.value,
            _token: document.querySelector('[name="_token"]')?.value,
        };

        try {
            var res = await fetch('{{ route('contacto.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': payload._token
                },
                body: JSON.stringify(payload),
            });

            var json = await res.json();

            if (json.success) {
                document.getElementById('contactSuccessMsg').textContent = json.message;
                okBox.classList.remove('d-none');
                okBox.classList.add('d-flex');
                form.reset();
            } else {
                document.getElementById('contactErrorMsg').textContent = json.message || 'No se pudo enviar el mensaje.';
                errBox.classList.remove('d-none');
                errBox.classList.add('d-flex');
            }
        } catch (error) {
            document.getElementById('contactErrorMsg').textContent = 'Error de red. Por favor intentá nuevamente o llamanos al 0381 4331665.';
            errBox.classList.remove('d-none');
            errBox.classList.add('d-flex');
        } finally {
            btn.disabled = false;
            btnText.classList.remove('d-none');
            btnSpin.classList.add('d-none');
            (okBox.classList.contains('d-flex') ? okBox : errBox).scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    });
});
</script>
@endpush

@push('styles')
<style>
    .contact-page-section {
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
    }

    .contact-hero-pill {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 18px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        color: #ffffff;
        font-weight: 600;
        font-size: 15px;
        border: 1px solid rgba(255, 255, 255, 0.14);
    }

    .contact-sidebar-card,
    .contact-branches-card,
    .contact-form-card,
    .contact-map-card {
        background: #ffffff;
        border: 1px solid #e6edf5;
        border-radius: 24px;
        box-shadow: 0 18px 45px rgba(42, 53, 71, 0.08);
    }

    .contact-sidebar-card,
    .contact-branches-card,
    .contact-form-card {
        padding: 32px;
    }

    .contact-sidebar-icon {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        background: #1e3a5f;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        flex-shrink: 0;
    }

    .contact-sidebar-icon--soft {
        background: #eef7ff;
        color: #1e3a5f;
    }

    .contact-info-list {
        display: grid;
        gap: 18px;
    }

    .contact-info-row {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 16px 18px;
        border-radius: 16px;
        background: #f8fbff;
        border: 1px solid #e7eef7;
    }

    .contact-info-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: rgba(73, 190, 255, 0.12);
        color: #1e3a5f;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .contact-branch-grid {
        display: grid;
        gap: 16px;
    }

    .contact-branch-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 18px;
        border-radius: 16px;
        background: #f8fbff;
        border: 1px solid #e7eef7;
    }

    .contact-branch-item i {
        font-size: 20px;
        margin-top: 3px;
    }

    .contact-form-note {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        border-radius: 14px;
        background: #f8fbff;
        border: 1px solid #e7eef7;
        color: #526176;
        font-size: 14px;
        font-weight: 500;
    }

    .contact-form-note i {
        color: #1e3a5f;
        font-size: 18px;
    }

    .contact-input {
        border: 1px solid #dbe5f0;
        border-radius: 14px;
        padding: 16px 18px;
        background: #ffffff;
        transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease;
    }

    .contact-input:focus {
        border-color: #1e3a5f;
        box-shadow: 0 0 0 0.25rem rgba(30, 58, 95, 0.08);
        background: #ffffff;
    }

    .contact-map-copy {
        padding: 32px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        min-height: 460px;
    }

    .contact-map-meta {
        display: grid;
        gap: 14px;
    }

    .contact-map-meta span {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        color: #1e3a5f;
    }

    @media (max-width: 991.98px) {
        .contact-sidebar-card,
        .contact-branches-card,
        .contact-form-card {
            padding: 24px;
        }

        .contact-map-copy {
            min-height: auto;
            padding: 24px;
        }
    }
</style>
@endpush
