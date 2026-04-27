@extends('layouts.cent-public')

@section('title', 'Preguntas frecuentes — CENT N°74')
@section('meta_description', 'Preguntas frecuentes sobre carreras, sedes, preinscripción y portal académico del CENT N°74 de Tucumán.')

@push('styles')
<style>
    [data-aos] { opacity: 0; transform: translateY(24px); transition: opacity .5s ease, transform .5s ease; }
    [data-aos].aos-animate { opacity: 1; transform: none; }
    .accordion-button {
        font-weight: 700;
        font-family: 'Outfit', sans-serif;
        font-size: 15px;
        color: var(--cent-ink);
        background: #fff;
    }
    .accordion-button:not(.collapsed) {
        color: var(--cent-blue);
        background: var(--cent-soft);
        box-shadow: none;
    }
    .accordion-button::after {
        filter: none;
    }
    .accordion-button:focus { box-shadow: none; }
</style>
@endpush

@section('content')

<section class="py-5 bg-light">
    <div class="container py-lg-5">
        <div class="row align-items-end g-4">
            <div class="col-lg-8" data-aos>
                <span class="section-badge bg-info-subtle text-info"><i class="ti ti-help me-1"></i>Ayuda</span>
                <h1 class="display-5 fw-bolder">Preguntas frecuentes</h1>
                <p class="fs-5 text-muted mb-0">Información útil para aspirantes, alumnos y docentes del CENT N°74.</p>
            </div>
            <div class="col-lg-4" data-aos>
                <div class="card cent-card">
                    <div class="card-body p-4">
                        <div class="d-flex gap-3 align-items-center">
                            <span class="feature-icon bg-primary-subtle text-primary flex-shrink-0">
                                <i class="ti ti-message-question"></i>
                            </span>
                            <div>
                                <strong class="d-block">¿No encontrás tu consulta?</strong>
                                <a href="{{ route('cent.contacto') }}" class="text-primary fw-bold small">Contactanos →</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 py-lg-10">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8" data-aos>

                @php
                $faqs = [
                    [
                        'cat'  => 'Preinscripción',
                        'icon' => 'ti-user-plus',
                        'color'=> 'primary',
                        'items'=> [
                            ['q'=>'¿Necesito ser afiliado para estudiar en el CENT?',
                             'a'=>'No. La preinscripción está abierta a toda la comunidad, independientemente de si sos o no afiliado a ATSA. Cada solicitud se revisa según la documentación presentada y la disponibilidad en la sede elegida.'],
                            ['q'=>'¿La preinscripción online confirma mi vacante?',
                             'a'=>'No. La preinscripción online registra tus datos y genera una ficha PDF con un código único. La inscripción definitiva se confirma luego de la revisión institucional, la aprobación del curso propedéutico y la entrega de la documentación requerida.'],
                            ['q'=>'¿Puedo elegir en qué sede cursar?',
                             'a'=>'Sí. En el formulario de preinscripción podés elegir la carrera y la sede de preferencia. La disponibilidad queda sujeta a revisión académica y a la oferta vigente en cada sede.'],
                            ['q'=>'¿Cómo consulto el estado de mi preinscripción?',
                             'a'=>'Podés verificar el estado usando el código que recibiste al enviar el formulario junto con tu DNI, en la sección "Consultar preinscripción" del sitio.'],
                        ],
                    ],
                    [
                        'cat'  => 'Ingreso y nivelatorio',
                        'icon' => 'ti-clipboard-check',
                        'color'=> 'success',
                        'items'=> [
                            ['q'=>'¿Qué es el curso propedéutico de nivelación?',
                             'a'=>'Es un curso obligatorio que marca el inicio del ciclo lectivo. Tiene una duración máxima de cuatro semanas, comienza durante la primera quincena de febrero, y aborda ciencias básicas aplicadas a la salud. Se requiere 80% de asistencia y nota mínima 6 en cada área para acceder a la condición de ingresante.'],
                            ['q'=>'¿Qué documentación debo presentar?',
                             'a'=>'DNI (frente y dorso), título secundario o constancia de título en trámite, fotos 4×4, certificado de buena conducta, residencia, apto psicofísico y constancias de vacunas (Hepatitis B y antitetánica). Consultá la página de Ingreso para el detalle completo.'],
                            ['q'=>'¿Puedo adjuntar documentación después de la preinscripción?',
                             'a'=>'Sí. Podés actualizar la documentación a través de la consulta de preinscripción usando tu código y DNI, siempre que el estado todavía lo permita.'],
                        ],
                    ],
                    [
                        'cat'  => 'Portal académico',
                        'icon' => 'ti-lock',
                        'color'=> 'info',
                        'items'=> [
                            ['q'=>'¿Cómo ingreso al portal académico?',
                             'a'=>'El portal es exclusivo para alumnos, docentes y directivos registrados por la institución. Se accede con el email institucional, DNI o número de legajo académico asignado por la sede.'],
                            ['q'=>'¿Dónde veo mis notas y asistencia?',
                             'a'=>'Una vez registrado como alumno activo, las notas, asistencia y comisiones estarán disponibles en el portal académico. Las actualizaciones las realiza el docente de cada materia.'],
                            ['q'=>'¿Qué hago si olvidé mi contraseña?',
                             'a'=>'En la pantalla de login encontrás la opción para recuperar el acceso. Si el problema persiste, comunicarte directamente con la sede o con la administración del sistema.'],
                        ],
                    ],
                    [
                        'cat'  => 'Carreras y título',
                        'icon' => 'ti-certificate',
                        'color'=> 'warning',
                        'items'=> [
                            ['q'=>'¿Los títulos están reconocidos oficialmente?',
                             'a'=>'Sí. Los títulos del CENT N°74 están reconocidos por el Ministerio de Educación de la Provincia de Tucumán. Son títulos de nivel terciario habilitantes para el ejercicio profesional en el sistema sanitario.'],
                            ['q'=>'¿Las carreras tienen prácticas profesionales?',
                             'a'=>'Sí. Todas las carreras contemplan trayectos de prácticas profesionalizantes en establecimientos del sistema sanitario provincial, mediante convenio institucional con SIPROSA.'],
                        ],
                    ],
                ];
                $faqIndex = 0;
                @endphp

                @foreach($faqs as $group)
                <div class="mb-5">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="rounded-circle bg-{{ $group['color'] }}-subtle text-{{ $group['color'] }} d-inline-flex align-items-center justify-content-center" style="width:36px;height:36px;font-size:16px;">
                            <i class="ti {{ $group['icon'] }}"></i>
                        </span>
                        <h2 class="h5 fw-bold mb-0">{{ $group['cat'] }}</h2>
                    </div>
                    <div class="accordion d-flex flex-column gap-3" id="faqGroup{{ $loop->index }}">
                        @foreach($group['items'] as $item)
                        @php $faqIndex++; @endphp
                        <div class="accordion-item border-0 shadow-sm rounded-4 overflow-hidden">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed rounded-4" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#faq{{ $faqIndex }}"
                                        aria-expanded="false">
                                    {{ $item['q'] }}
                                </button>
                            </h3>
                            <div id="faq{{ $faqIndex }}" class="accordion-collapse collapse"
                                 data-bs-parent="#faqGroup{{ $loop->parent->index }}">
                                <div class="accordion-body text-muted" style="font-size:14.5px;line-height:1.7;">
                                    {{ $item['a'] }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach

            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4" data-aos>
                <div class="card cent-card sticky-top" style="top:110px;">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4">Accesos rápidos</h4>
                        <div class="d-flex flex-column gap-3">
                            <a href="{{ route('cent.preinscripcion') }}" class="btn btn-cent w-100">
                                <i class="ti ti-user-plus me-2"></i>Iniciar preinscripción
                            </a>
                            <a href="{{ route('cent.requisitos') }}" class="btn btn-outline-cent w-100">
                                <i class="ti ti-file-check me-2"></i>Ver requisitos
                            </a>
                            <a href="{{ route('cent.carreras') }}" class="btn btn-outline-cent w-100">
                                <i class="ti ti-school me-2"></i>Ver carreras
                            </a>
                            <a href="{{ route('cent.sedes') }}" class="btn btn-outline-cent w-100">
                                <i class="ti ti-map-pin me-2"></i>Ver sedes
                            </a>
                            <a href="{{ route('cent.login') }}" class="btn btn-outline-cent w-100">
                                <i class="ti ti-lock me-2"></i>Portal académico
                            </a>
                        </div>
                        <hr>
                        <div class="cent-muted-box p-4">
                            <p class="fw-bold mb-1" style="font-size:14px;">¿Otra consulta?</p>
                            <p class="text-muted mb-3" style="font-size:13px;">Comunicate con la sede más cercana o envianos tu consulta.</p>
                            <a href="{{ route('cent.contacto') }}" class="btn btn-sm btn-outline-cent w-100">
                                <i class="ti ti-phone me-1"></i>Ver contacto
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-cent-blue text-white">
    <div class="container text-center" data-aos>
        <h2 class="fw-bolder text-white">¿Listo para empezar?</h2>
        <p class="fs-5 mb-4" style="color:rgba(255,255,255,.75);">Completá tu preinscripción online o comunicarte con la sede más cercana.</p>
        <a href="{{ route('cent.contacto') }}" class="btn btn-light px-4 me-2">
            <i class="ti ti-phone me-1"></i>Contactar
        </a>
        <a href="{{ route('cent.preinscripcion') }}" class="btn btn-outline-light px-4">
            <i class="ti ti-user-plus me-1"></i>Preinscribirme
        </a>
    </div>
</section>

@endsection

@push('scripts')
<script>
    (function () {
        const els = document.querySelectorAll('[data-aos]');
        if (!els.length) return;
        const io = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('aos-animate'); io.unobserve(e.target); } });
        }, { threshold: 0.1 });
        els.forEach(el => io.observe(el));
    })();
</script>
@endpush
