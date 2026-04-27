@extends('layouts.cent-public')

@section('title', 'Ingreso y requisitos - CENT N°74')
@section('meta_description', 'Requisitos de ingreso, nivelación y documentación para preinscribirse en el CENT N°74.')

@section('content')
<section class="py-5 bg-light">
    <div class="container py-lg-5">
        <div class="row align-items-end g-4">
            <div class="col-lg-8">
                <span class="section-badge bg-primary-subtle text-primary">Ingreso</span>
                <h1 class="display-5 fw-bolder">Todo lo que necesitás para empezar</h1>
                <p class="fs-5 text-muted text-justify mb-0">El proceso de ingreso al CENT N°74 combina preinscripción online, revisión documental y validación académica en la sede correspondiente. Acá tenés el recorrido completo para llegar con todo listo.</p>
            </div>
            <div class="col-lg-4">
                <div class="cent-muted-box p-4 h-100">
                    <div class="small text-uppercase fw-bold text-primary mb-2">Ingreso {{ now()->format('Y') }}</div>
                    <h4 class="fw-bold mb-2">Seguimiento ordenado</h4>
                    <p class="text-muted mb-0">Cada aspirante recibe un código para descargar su ficha, consultar el estado del trámite y actualizar documentación si la sede lo solicita.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 py-lg-10">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-7">
                <div class="card cent-card mb-4">
                    <div class="card-body p-4 p-lg-5">
                        <span class="section-badge bg-success-subtle text-success">Paso 1</span>
                        <h2 class="fw-bold mb-2">Documentación solicitada</h2>
                        <p class="text-muted text-justify mb-4">Antes de completar la preinscripción, reuní estos archivos y constancias. Vas a poder adjuntar los documentos principales desde el formulario y completar el resto cuando la sede lo indique.</p>

                        <div class="d-flex flex-column gap-4">
                            @foreach([
                                ['icon' => 'ti-id', 'title' => 'DNI', 'text' => 'Fotocopia del Documento Nacional de Identidad, frente y dorso, preferentemente en un solo archivo.'],
                                ['icon' => 'ti-certificate', 'title' => 'Título o constancia del secundario', 'text' => 'Analítico, constancia de título en trámite o la documentación equivalente que corresponda a tu situación.'],
                                ['icon' => 'ti-file-description', 'title' => 'Fichas institucionales', 'text' => 'Ficha general de ingreso y formularios internos que la sede solicite para completar la inscripción definitiva.'],
                                ['icon' => 'ti-photo', 'title' => 'Fotos carnet', 'text' => 'Dos fotos 4x4 actualizadas para el legajo y documentación institucional.'],
                                ['icon' => 'ti-heart-handshake', 'title' => 'Apto y residencia', 'text' => 'Certificado de residencia y apto psicofísico expedido por institución oficial, cuando la carrera o la sede lo requieran.'],
                                ['icon' => 'ti-vaccine', 'title' => 'Vacunación', 'text' => 'Constancia de vacunación contra Hepatitis B y demás certificados sanitarios solicitados para las prácticas.'],
                            ] as $item)
                                <div class="d-flex gap-3">
                                    <span class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                                        <i class="ti {{ $item['icon'] }}"></i>
                                    </span>
                                    <div>
                                        <h4 class="fw-bold mb-1">{{ $item['title'] }}</h4>
                                        <p class="text-muted text-justify mb-0">{{ $item['text'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card cent-card">
                    <div class="card-body p-4 p-lg-5">
                        <span class="section-badge bg-warning-subtle text-warning">Paso 2</span>
                        <h2 class="fw-bold mb-2">Elegí carrera y sede</h2>
                        <p class="text-muted text-justify mb-4">No todas las carreras se dictan en todas las sedes. En la ficha online se filtran automáticamente las opciones habilitadas según la propuesta académica que selecciones.</p>

                        <div class="row g-3">
                            @foreach($carreras as $carrera)
                                <div class="col-md-6">
                                    <div class="p-3 rounded-4 bg-light h-100 border">
                                        <strong class="d-block mb-1">{{ $carrera->name }}</strong>
                                        <div class="small text-muted mb-2">{{ $carrera->duration }}</div>
                                        <div class="small text-muted">
                                            @if($carrera->centSedes->isNotEmpty())
                                                {{ $carrera->centSedes->pluck('nombre')->implode(' · ') }}
                                            @else
                                                Sedes a confirmar por administración.
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card cent-card sticky-top" style="top: 110px;">
                    <div class="card-body p-4 p-lg-5">
                        <span class="section-badge bg-info-subtle text-info">Paso 3</span>
                        <h2 class="fw-bold">Después de enviar la ficha</h2>
                        <p class="text-muted text-justify">La preinscripción no confirma automáticamente la vacante. La sede revisa datos, documentación y disponibilidad académica antes de avanzar con la inscripción definitiva.</p>

                        <div class="d-flex flex-column gap-3 my-4">
                            <div class="d-flex gap-3"><span class="badge bg-primary">1</span><span>Completás la ficha y recibís un código único de seguimiento.</span></div>
                            <div class="d-flex gap-3"><span class="badge bg-primary">2</span><span>La sede valida la documentación y te informa si falta algo.</span></div>
                            <div class="d-flex gap-3"><span class="badge bg-primary">3</span><span>Podés descargar la ficha PDF y volver a consultar el trámite online.</span></div>
                            <div class="d-flex gap-3"><span class="badge bg-primary">4</span><span>Si todo está correcto, se habilita el circuito de inscripción y acceso institucional.</span></div>
                        </div>

                        <div class="cent-muted-box p-4 mb-4">
                            <h5 class="fw-bold mb-3">Antes de empezar</h5>
                            <ul class="text-muted mb-0 ps-3">
                                <li>Verificá que tu email y DNI estén correctos.</li>
                                <li>Tené listo el archivo del DNI y del secundario.</li>
                                <li>Revisá en qué sedes se dicta la carrera elegida.</li>
                            </ul>
                        </div>

                        <a href="{{ route('cent.preinscripcion') }}" class="btn btn-cent w-100 mb-2">Iniciar preinscripción</a>
                        <a href="{{ route('cent.preinscripcion.consulta') }}" class="btn btn-outline-cent w-100 mb-2">Consultar una preinscripción</a>
                        <a href="https://cent74atsatucuman.ar/FICHA%20DE%20INSCRIPCION%20ASPIRANTES%202026.pdf" target="_blank" rel="noopener" class="btn btn-light w-100">Descargar ficha 2026</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
