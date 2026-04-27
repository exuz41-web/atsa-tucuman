@extends('layouts.cent-public')

@section('title', 'Mesas de examen — CENT N°74')
@section('meta_description', 'Fechas de mesas de examen, inscripciones, condición de regularidad y requisitos para rendir en el CENT N°74 de Tucumán.')

@push('styles')
<style>
    [data-aos] { opacity: 0; transform: translateY(24px); transition: opacity .5s ease, transform .5s ease; }
    [data-aos].aos-animate { opacity: 1; transform: none; }

    .periodo-card {
        border-radius: 20px;
        padding: 24px;
        height: 100%;
        position: relative;
        overflow: hidden;
    }
    .periodo-card::before {
        content: attr(data-month);
        position: absolute;
        right: -10px;
        bottom: -20px;
        font-family: 'Outfit', sans-serif;
        font-size: 90px;
        font-weight: 900;
        opacity: .06;
        line-height: 1;
        color: currentColor;
    }
    .condicion-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 50px;
        font-weight: 800;
        font-size: 13px;
    }
    .timeline-dot {
        width: 14px; height: 14px;
        border-radius: 50%;
        flex-shrink: 0;
        margin-top: 5px;
    }
</style>
@endpush

@section('content')

{{-- Hero --}}
<section class="py-5 bg-light">
    <div class="container py-lg-4">
        <div class="row align-items-end g-4">
            <div class="col-lg-8" data-aos>
                <span class="section-badge bg-warning-subtle text-warning"><i class="ti ti-writing me-1"></i>Exámenes finales</span>
                <h1 class="display-5 fw-bolder mb-2">Mesas de examen</h1>
                <p class="fs-5 text-muted text-justify mb-0">Fechas de inscripción, períodos de examen y requisitos de regularidad. Toda la información para que puedas organizar tus finales en el CENT N°74.</p>
            </div>
            <div class="col-lg-4" data-aos>
                <div class="card cent-card">
                    <div class="card-body p-4">
                        <div class="d-flex gap-3 align-items-center">
                            <span class="feature-icon bg-warning-subtle text-warning flex-shrink-0"><i class="ti ti-calendar-event"></i></span>
                            <div>
                                <strong class="d-block">3 turnos de examen</strong>
                                <p class="text-muted mb-0" style="font-size:13px;">Feb/Mar · Jul/Ago · Nov/Dic</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ─── Períodos del año ─── --}}
<section class="py-5 py-lg-10">
    <div class="container">
        <div class="mb-5" data-aos>
            <span class="section-badge bg-warning-subtle text-warning"><i class="ti ti-calendar me-1"></i>Calendario de exámenes</span>
            <h2 class="fw-bolder display-6 mb-2">Tres turnos en el año</h2>
            <p class="text-muted">Las inscripciones se realizan en cada sede 3 días hábiles antes del inicio del turno. Para Capital también existe inscripción virtual.</p>
        </div>

        <div class="row g-4 mb-5">
            @php
            $periodos = [
                [
                    'month'    => 'Feb',
                    'titulo'   => 'Febrero / Marzo',
                    'icon'     => 'ti-sun',
                    'color'    => 'warning',
                    'bg'       => '#fffbf0',
                    'border'   => '#f0c040',
                    'inscripcion' => 'Última semana de enero',
                    'examen'   => 'Primera quincena de febrero hasta primera semana de marzo',
                    'nota'     => 'Primera oportunidad del año. Ideal para materias cursadas en el ciclo anterior.',
                    'requisito'=> 'Libre deuda del ciclo lectivo anterior.',
                ],
                [
                    'month'    => 'Jul',
                    'titulo'   => 'Julio / Agosto',
                    'icon'     => 'ti-snowflake',
                    'color'    => 'info',
                    'bg'       => '#f0f8ff',
                    'border'   => '#49beff',
                    'inscripcion' => 'Del 21 al 23 de julio en cada sede',
                    'examen'   => 'Del 25 de julio al 10 de agosto',
                    'nota'     => 'Turno de invierno. Inscripción virtual disponible para la sede Capital.',
                    'requisito'=> 'Libre deuda. Condición regular o libre en la materia.',
                ],
                [
                    'month'    => 'Nov',
                    'titulo'   => 'Noviembre / Diciembre',
                    'icon'     => 'ti-leaf',
                    'color'    => 'success',
                    'bg'       => '#f0faf5',
                    'border'   => '#27ae80',
                    'inscripcion' => 'Del 10 al 14 de noviembre en cada sede',
                    'examen'   => 'Desde la tercera semana de noviembre hasta mediados de diciembre',
                    'nota'     => 'Último turno del año. Clave para regularizar antes del cierre del ciclo lectivo.',
                    'requisito'=> 'Libre deuda. Condición regular o libre.',
                ],
            ];
            @endphp

            @foreach($periodos as $p)
            <div class="col-lg-4" data-aos>
                <div class="periodo-card h-100" data-month="{{ $p['month'] }}"
                     style="background:{{ $p['bg'] }}; border: 2px solid {{ $p['border'] }};">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="rounded-circle bg-{{ $p['color'] }}-subtle text-{{ $p['color'] }} d-inline-flex align-items-center justify-content-center" style="width:52px;height:52px;font-size:24px;">
                            <i class="ti {{ $p['icon'] }}"></i>
                        </span>
                        <h3 class="fw-bolder mb-0" style="font-size:20px;">{{ $p['titulo'] }}</h3>
                    </div>
                    <div class="d-flex flex-column gap-3">
                        <div>
                            <small class="text-muted fw-bold text-uppercase" style="font-size:11px;letter-spacing:.6px;">Inscripción</small>
                            <p class="fw-bold mb-0" style="font-size:14px;">{{ $p['inscripcion'] }}</p>
                        </div>
                        <div>
                            <small class="text-muted fw-bold text-uppercase" style="font-size:11px;letter-spacing:.6px;">Período de examen</small>
                            <p class="fw-bold mb-0" style="font-size:14px;">{{ $p['examen'] }}</p>
                        </div>
                        <div>
                            <small class="text-muted fw-bold text-uppercase" style="font-size:11px;letter-spacing:.6px;">Requisito</small>
                            <p class="text-muted mb-0" style="font-size:13px;">{{ $p['requisito'] }}</p>
                        </div>
                        <p class="text-muted mb-0" style="font-size:13px;">{{ $p['nota'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Próximas mesas desde BD --}}
        @if($proximasMesas->isNotEmpty())
        <div data-aos>
            <h3 class="fw-bold mb-4 d-flex align-items-center gap-2">
                <span class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width:36px;height:36px;font-size:16px;"><i class="ti ti-clock"></i></span>
                Próximas fechas publicadas
            </h3>
            <div class="row g-3">
                @foreach($proximasMesas as $evento)
                @php
                $tipoColors = ['mesa'=>'warning','inscripcion'=>'success','otro'=>'secondary'];
                $tipoIcons  = ['mesa'=>'ti-writing','inscripcion'=>'ti-user-plus','otro'=>'ti-calendar'];
                $color = $tipoColors[$evento->tipo] ?? 'primary';
                $icon  = $tipoIcons[$evento->tipo]  ?? 'ti-calendar';
                @endphp
                <div class="col-md-6">
                    <div class="card cent-card">
                        <div class="card-body p-4">
                            <div class="d-flex gap-3">
                                <span class="rounded-circle bg-{{ $color }}-subtle text-{{ $color }} d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width:46px;height:46px;font-size:20px;">
                                    <i class="ti {{ $icon }}"></i>
                                </span>
                                <div class="flex-grow-1">
                                    <h4 class="fw-bold mb-1" style="font-size:15px;">{{ $evento->titulo }}</h4>
                                    <div class="d-flex gap-3 flex-wrap">
                                        <span class="text-muted" style="font-size:13px;">
                                            <i class="ti ti-calendar me-1"></i>{{ $evento->fecha_inicio->format('d/m/Y') }}
                                            @if($evento->fecha_fin && $evento->fecha_fin->format('Y-m-d') !== $evento->fecha_inicio->format('Y-m-d'))
                                                — {{ $evento->fecha_fin->format('d/m/Y') }}
                                            @endif
                                        </span>
                                        @if($evento->sede)
                                            <span class="text-muted" style="font-size:13px;"><i class="ti ti-map-pin me-1"></i>{{ $evento->sede->ciudad }}</span>
                                        @endif
                                        @if($evento->carrera)
                                            <span class="badge bg-light text-primary border" style="font-size:11px;">{{ $evento->carrera->name }}</span>
                                        @endif
                                    </div>
                                    @if($evento->descripcion)
                                        <p class="text-muted mt-1 mb-0" style="font-size:13px;">{{ $evento->descripcion }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Avisos de mesas desde AvisoCent --}}
        @if($avisosMesas->isNotEmpty())
        <div class="mt-5" data-aos>
            <h3 class="fw-bold mb-4 d-flex align-items-center gap-2">
                <span class="rounded-circle bg-warning-subtle text-warning d-inline-flex align-items-center justify-content-center" style="width:36px;height:36px;font-size:16px;"><i class="ti ti-speakerphone"></i></span>
                Avisos recientes de exámenes
            </h3>
            <div class="row g-3">
                @foreach($avisosMesas as $aviso)
                @php $cfg = $aviso->tipo_config; @endphp
                <div class="col-md-6">
                    <article class="card cent-card h-100 {{ $aviso->destacado ? 'border border-warning border-2' : '' }}">
                        <div class="card-body p-4">
                            <div class="d-flex gap-2 mb-2 flex-wrap">
                                <span class="badge bg-{{ $cfg['color'] }}-subtle text-{{ $cfg['color'] }} rounded-pill" style="font-size:11px;">
                                    <i class="ti {{ $cfg['icon'] }} me-1"></i>{{ $cfg['label'] }}
                                </span>
                                @if($aviso->destacado)<span class="badge bg-warning text-dark rounded-pill" style="font-size:10px;"><i class="ti ti-star me-1"></i>Destacado</span>@endif
                            </div>
                            <h4 class="fw-bold mb-2" style="font-size:15px;">{{ $aviso->titulo }}</h4>
                            <p class="text-muted mb-0" style="font-size:13.5px;line-height:1.6;">{{ $aviso->contenido }}</p>
                        </div>
                        <div class="card-footer bg-white border-0 p-4 pt-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted"><i class="ti ti-calendar me-1"></i>{{ $aviso->created_at->format('d/m/Y') }}</small>
                                <small class="text-muted">
                                    {{ $aviso->carrera?->name ?: ($aviso->sede?->ciudad ?: 'Todas las sedes') }}
                                </small>
                            </div>
                        </div>
                    </article>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

{{-- ─── Regularidad y condiciones ─── --}}
<section class="py-5 py-lg-10" style="background:#f4f7fb;">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6" data-aos>
                <span class="section-badge bg-danger-subtle text-danger"><i class="ti ti-alert-triangle me-1"></i>Regularidad</span>
                <h2 class="fw-bolder display-6 mb-3">Condición de alumno regular</h2>
                <p class="text-muted text-justify mb-4">Para poder inscribirse a una mesa de examen final, el alumno debe cumplir con los siguientes requisitos establecidos por el Reglamento Académico del CENT N°74:</p>

                <div class="d-flex flex-column gap-3">
                    @foreach([
                        ['icon'=>'ti-check','color'=>'success','titulo'=>'80% de asistencia','texto'=>'Asistencia obligatoria mínima del 80% en la materia. Las inasistencias injustificadas pueden hacer perder la regularidad.'],
                        ['icon'=>'ti-check','color'=>'success','titulo'=>'Parciales aprobados','texto'=>'Aprobar los parciales con nota mínima 4. Con notas entre 4 y 6 quedás regular y debés rendir el final.'],
                        ['icon'=>'ti-check','color'=>'success','titulo'=>'Libre deuda','texto'=>'No tener deuda de cuotas del año en curso ni del ciclo anterior para poder inscribirte a la mesa.'],
                        ['icon'=>'ti-check','color'=>'success','titulo'=>'Sin instancias pendientes','texto'=>'Haber cumplido todos los trabajos prácticos obligatorios y evaluaciones parciales de la materia.'],
                    ] as $req)
                    <div class="d-flex gap-3">
                        <span class="rounded-circle bg-{{ $req['color'] }}-subtle text-{{ $req['color'] }} d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width:38px;height:38px;">
                            <i class="ti {{ $req['icon'] }}"></i>
                        </span>
                        <div>
                            <h4 class="fw-bold mb-1" style="font-size:14px;">{{ $req['titulo'] }}</h4>
                            <p class="text-muted mb-0" style="font-size:13px;">{{ $req['texto'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="col-lg-6" data-aos>
                <span class="section-badge bg-info-subtle text-info"><i class="ti ti-book me-1"></i>Escala de notas</span>
                <h2 class="fw-bolder display-6 mb-3">¿Cómo funciona el sistema de calificaciones?</h2>

                <div class="d-flex flex-column gap-3 mb-5">
                    @php
                    $escala = [
                        ['rango'=>'1 a 3','label'=>'Reprobado','color'=>'danger','texto'=>'La materia no se aprueba. Si fue en parcial, se puede recuperar una vez. Si fue en final, se debe recursar.'],
                        ['rango'=>'4 a 6','label'=>'Regular','color'=>'warning','texto'=>'Aprobaste el cursado. Quedás regular y debés rendir un examen final para aprobar la materia definitivamente.'],
                        ['rango'=>'7 a 10','label'=>'Promovido','color'=>'success','texto'=>'Si obtenés 7 o más en todos los parciales podés promover directamente la materia sin rendir final (sujeto a reglamento de cada carrera).'],
                    ];
                    @endphp
                    @foreach($escala as $e)
                    <div class="p-4 rounded-4 border-start border-4 border-{{ $e['color'] }} bg-white">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <span class="condicion-badge bg-{{ $e['color'] }}-subtle text-{{ $e['color'] }}">
                                {{ $e['rango'] }}
                            </span>
                            <strong>{{ $e['label'] }}</strong>
                        </div>
                        <p class="text-muted mb-0" style="font-size:13.5px;">{{ $e['texto'] }}</p>
                    </div>
                    @endforeach
                </div>

                <div class="card cent-card border-2" style="border-color: var(--cent-sky) !important;">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-2"><i class="ti ti-info-circle text-info me-2"></i>Alumno libre</h4>
                        <p class="text-muted mb-0" style="font-size:13.5px;">Si perdiste la regularidad (por inasistencias o reprobar el recuperatorio), podés presentarte como <strong>alumno libre</strong>. El examen libre incluye una instancia escrita y una oral ante tribunal. Consultá en tu sede las condiciones específicas.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ─── Cómo inscribirse ─── --}}
<section class="py-5 py-lg-10">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5" data-aos>
                <span class="section-badge bg-success-subtle text-success"><i class="ti ti-user-check me-1"></i>Proceso</span>
                <h2 class="fw-bolder display-6">¿Cómo me inscribo a una mesa?</h2>
                <p class="text-muted text-justify">La inscripción a mesas de examen se realiza directamente en la sede donde cursás. Llevá tu DNI y verificá que estés al día con la documentación.</p>
                <div class="cent-muted-box p-4 mt-4">
                    <p class="fw-bold mb-1"><i class="ti ti-map-pin text-primary me-1"></i>¿En qué sede me inscribo?</p>
                    <p class="text-muted mb-0" style="font-size:13.5px;">Siempre en la sede donde sos alumno regular, salvo que la carrera ofrezca la materia solo en Capital. Consultá directamente con tu delegación.</p>
                </div>
            </div>
            <div class="col-lg-7" data-aos>
                <div class="ps-0 ps-lg-4">
                    @php
                    $pasos = [
                        ['n'=>'1','color'=>'primary','titulo'=>'Verificá tu condición','texto'=>'Confirmá que tenés 80% de asistencia, los parciales aprobados y sin deuda. Si tenés dudas, preguntá en la secretaría de tu sede antes del período de inscripción.'],
                        ['n'=>'2','color'=>'info','titulo'=>'Consultá las fechas','texto'=>'Las fechas exactas de inscripción y de los turnos se publican en este sitio, en el portal académico y en la sede. Controlá las novedades periódicamente.'],
                        ['n'=>'3','color'=>'success','titulo'=>'Presentate en la sede','texto'=>'Acercate a la secretaría de tu delegación en los días habilitados para inscripción (normalmente 3 días hábiles antes del inicio del turno). Llevá tu DNI.'],
                        ['n'=>'4','color'=>'warning','titulo'=>'Inscripción virtual (Capital)','texto'=>'La sede Capital ofrece la posibilidad de inscripción virtual. Consultá en tu sede si está habilitada para el turno correspondiente.'],
                        ['n'=>'5','color'=>'danger','titulo'=>'Rendí el examen','texto'=>'El examen final puede ser oral, escrito o ambos según la materia. El tribunal lo integran el docente de la materia y al menos un titular más. La nota mínima para aprobar es 4.'],
                    ];
                    @endphp
                    <div class="d-flex flex-column gap-3">
                        @foreach($pasos as $paso)
                        <div class="card cent-card">
                            <div class="card-body p-4">
                                <div class="d-flex gap-3">
                                    <span class="rounded-circle bg-{{ $paso['color'] }} text-white d-inline-flex align-items-center justify-content-center fw-bolder flex-shrink-0"
                                          style="width:42px;height:42px;font-family:'Outfit',sans-serif;font-size:17px;">{{ $paso['n'] }}</span>
                                    <div>
                                        <h4 class="fw-bold mb-1" style="font-size:14.5px;">{{ $paso['titulo'] }}</h4>
                                        <p class="text-muted mb-0" style="font-size:13px;">{{ $paso['texto'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-5 bg-cent-blue text-white">
    <div class="container text-center" data-aos>
        <h2 class="fw-bolder text-white">¿Tenés dudas sobre tu situación académica?</h2>
        <p class="fs-5 mb-4" style="color:rgba(255,255,255,.75);">Accedé al portal académico para ver tus notas y asistencia, o comunicarte con la sede.</p>
        <a href="{{ route('cent.login') }}" class="btn btn-light btn-lg px-5 me-2 fw-bold rounded-3">
            <i class="ti ti-login me-2"></i>Portal académico
        </a>
        <a href="{{ route('cent.contacto') }}" class="btn btn-outline-light btn-lg px-4 rounded-3">
            <i class="ti ti-phone me-2"></i>Contactar sede
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
