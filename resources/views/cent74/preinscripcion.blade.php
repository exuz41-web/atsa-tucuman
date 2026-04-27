@extends('layouts.cent-public')

@section('title', 'Preinscripción CENT N°74')
@section('meta_description', 'Formulario público de preinscripción al CENT N°74.')

@push('styles')
<style>
    .pre-step {
        border-radius: 18px;
        background: #f6f8fb;
        border: 1px solid #e5eaef;
        padding: 18px;
        height: 100%;
    }

    .pre-step-number {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--cent-blue);
        color: #fff;
        font-weight: 900;
    }

    .upload-card {
        border: 1px dashed #9eb8d8;
        background: #f8fbff;
        border-radius: 18px;
        padding: 20px;
        min-height: 135px;
        transition: all .2s ease;
    }

    .upload-card:hover {
        border-color: var(--cent-blue);
        background: #ecf7ff;
    }

    .upload-card input {
        cursor: pointer;
    }

    .file-feedback {
        display: none;
        margin-top: 10px;
        border-radius: 12px;
        background: #e8f0fe;
        color: #1e3a5f;
        padding: 9px 12px;
        font-size: 13px;
        font-weight: 700;
    }

    .upload-card.has-file {
        border-color: #13deb9;
        background: #effcf8;
    }

    .summary-card {
        border-radius: 18px;
        background: linear-gradient(135deg, rgba(30, 58, 95, .06), rgba(73, 190, 255, .14));
        border: 1px solid rgba(30, 58, 95, .1);
        padding: 18px;
    }
</style>
@endpush

@section('content')
<section class="py-5 bg-light">
    <div class="container py-lg-5">
        <div class="row align-items-end g-4">
            <div class="col-lg-8">
                <span class="section-badge bg-success-subtle text-success">
                    <i class="ti ti-file-pencil"></i> Ingreso online
                </span>
                <h1 class="display-5 fw-bolder">Ficha de preinscripción</h1>
                <p class="fs-5 text-muted mb-0">
                    Completá tus datos, elegí carrera y sede, adjuntá la documentación principal y descargá tu ficha para seguir el trámite con la institución.
                </p>
            </div>
            <div class="col-lg-4">
                <div class="card cent-card">
                    <div class="card-body p-4">
                        <div class="d-flex gap-3 align-items-center">
                            <span class="feature-icon bg-primary-subtle text-primary flex-shrink-0"><i class="ti ti-calendar-stats"></i></span>
                            <div>
                                <h4 class="fw-bold mb-1">Ciclo {{ now()->format('Y') }}</h4>
                                <p class="text-muted mb-0">La vacante queda sujeta a revisión institucional.</p>
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
            <div class="col-lg-4">
                <div class="card cent-card sticky-top" style="top: 110px;">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4">Proceso de preinscripción</h4>
                        <div class="d-flex flex-column gap-3">
                            <div class="pre-step">
                                <div class="d-flex gap-3">
                                    <span class="pre-step-number">1</span>
                                    <div>
                                        <strong>Carrera y sede</strong>
                                        <p class="text-muted small mb-0">Elegí la propuesta y la sede donde querés cursar.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="pre-step">
                                <div class="d-flex gap-3">
                                    <span class="pre-step-number">2</span>
                                    <div>
                                        <strong>Datos personales</strong>
                                        <p class="text-muted small mb-0">Completá la información para contacto y validación.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="pre-step">
                                <div class="d-flex gap-3">
                                    <span class="pre-step-number">3</span>
                                    <div>
                                        <strong>Situación educativa</strong>
                                        <p class="text-muted small mb-0">Indicá tu formación y datos complementarios.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="pre-step">
                                <div class="d-flex gap-3">
                                    <span class="pre-step-number">4</span>
                                    <div>
                                        <strong>Documentación</strong>
                                        <p class="text-muted small mb-0">DNI y título o constancia son obligatorios.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <h5 class="fw-bold">Archivos permitidos</h5>
                        <p class="text-muted mb-0">PDF, JPG o PNG. Máximo 4 MB por archivo.</p>

                        <hr>

                        <div class="cent-muted-box p-3 mb-3">
                            <div class="small text-uppercase fw-bold text-primary mb-2">Tip útil</div>
                            <p class="text-muted small mb-0">Si una carrera se dicta sólo en determinadas filiales, el formulario va a mostrar únicamente esas opciones para evitar errores de carga.</p>
                        </div>

                        <a href="{{ route('cent.preinscripcion.consulta') }}" class="btn btn-outline-cent w-100">
                            <i class="ti ti-search me-1"></i> Consultar una preinscripción
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                @if($errors->any())
                    <div class="alert alert-danger rounded-4">
                        <strong>Revisá la solicitud.</strong> Hay campos obligatorios, archivos pendientes o datos por corregir.
                    </div>
                @endif

                <form action="{{ route('cent.preinscripcion.guardar') }}" method="POST" enctype="multipart/form-data" class="card cent-card">
                    @csrf
                    <div class="card-body p-4 p-lg-5">
                        <section class="mb-5">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <span class="pre-step-number">1</span>
                                <div>
                                    <h2 class="h4 fw-bold mb-0">Carrera y sede</h2>
                                    <p class="text-muted mb-0">Seleccioná la propuesta académica que querés cursar.</p>
                                </div>
                            </div>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Carrera</label>
                                    <select name="carrera_id" id="carrera_id" class="form-select @error('carrera_id') is-invalid @enderror" required>
                                        <option value="">Seleccionar carrera</option>
                                        @foreach($carreras as $carrera)
                                            <option value="{{ $carrera->id }}" data-sedes="{{ $carrera->centSedes->pluck('id')->implode(',') }}" @selected(old('carrera_id') == $carrera->id)>{{ $carrera->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('carrera_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Sede</label>
                                    <select name="cent_sede_id" id="cent_sede_id" class="form-select @error('cent_sede_id') is-invalid @enderror" required>
                                        <option value="">Seleccionar sede</option>
                                        @foreach($sedes as $sede)
                                            <option value="{{ $sede->id }}" data-sede-option="1" @selected(old('cent_sede_id') == $sede->id)>{{ $sede->nombre }} - {{ $sede->ciudad }}</option>
                                        @endforeach
                                    </select>
                                    @error('cent_sede_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <div class="summary-card">
                                        <div class="small text-uppercase fw-bold text-primary mb-2">Resumen de elección</div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="small text-muted">Carrera seleccionada</div>
                                                <div class="fw-bold" data-summary-carrera>Elegí una carrera para continuar.</div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="small text-muted">Sedes habilitadas</div>
                                                <div class="fw-bold" data-summary-sedes>Se mostrarán una vez que selecciones la carrera.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section class="mb-5">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <span class="pre-step-number">2</span>
                                <div>
                                    <h2 class="h4 fw-bold mb-0">Datos personales</h2>
                                    <p class="text-muted mb-0">Usaremos estos datos para contactarte durante la revisión.</p>
                                </div>
                            </div>
                            <div class="row g-4">
                                <div class="col-md-8">
                                    <label class="form-label fw-bold">Apellido y nombre</label>
                                    <input name="apellido_nombre" value="{{ old('apellido_nombre') }}" class="form-control @error('apellido_nombre') is-invalid @enderror" required>
                                    @error('apellido_nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Fecha de nacimiento</label>
                                    <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" class="form-control @error('fecha_nacimiento') is-invalid @enderror">
                                    @error('fecha_nacimiento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Nacionalidad</label>
                                    <input name="nacionalidad" value="{{ old('nacionalidad', 'Argentina') }}" class="form-control @error('nacionalidad') is-invalid @enderror">
                                    @error('nacionalidad')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Estado civil</label>
                                    <input name="estado_civil" value="{{ old('estado_civil') }}" class="form-control @error('estado_civil') is-invalid @enderror">
                                    @error('estado_civil')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Tipo de documento</label>
                                    <input name="tipo_documento" value="{{ old('tipo_documento', 'DNI') }}" class="form-control @error('tipo_documento') is-invalid @enderror" required>
                                    @error('tipo_documento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">N° de documento</label>
                                    <input name="dni" value="{{ old('dni') }}" class="form-control @error('dni') is-invalid @enderror" required>
                                    @error('dni')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Email</label>
                                    <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Teléfono</label>
                                    <input name="telefono" value="{{ old('telefono') }}" class="form-control @error('telefono') is-invalid @enderror">
                                    @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label fw-bold">Domicilio particular</label>
                                    <input name="domicilio" value="{{ old('domicilio') }}" class="form-control @error('domicilio') is-invalid @enderror">
                                    @error('domicilio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Localidad</label>
                                    <input name="localidad" value="{{ old('localidad') }}" class="form-control @error('localidad') is-invalid @enderror">
                                    @error('localidad')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </section>

                        <section class="mb-5">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <span class="pre-step-number">3</span>
                                <div>
                                    <h2 class="h4 fw-bold mb-0">Datos educativos y laborales</h2>
                                    <p class="text-muted mb-0">Completá tu situación actual para orientar la revisión.</p>
                                </div>
                            </div>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Establecimiento donde trabaja</label>
                                    <input name="establecimiento_laboral" value="{{ old('establecimiento_laboral') }}" class="form-control @error('establecimiento_laboral') is-invalid @enderror">
                                    @error('establecimiento_laboral')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Nivel de estudios</label>
                                    <input name="nivel_estudios" value="{{ old('nivel_estudios') }}" class="form-control @error('nivel_estudios') is-invalid @enderror">
                                    @error('nivel_estudios')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Título secundario</label>
                                    <input name="titulo_secundario" value="{{ old('titulo_secundario') }}" class="form-control @error('titulo_secundario') is-invalid @enderror">
                                    @error('titulo_secundario')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Observaciones</label>
                                    <textarea name="observaciones_alumno" rows="4" class="form-control @error('observaciones_alumno') is-invalid @enderror">{{ old('observaciones_alumno') }}</textarea>
                                    @error('observaciones_alumno')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </section>

                        <section>
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <span class="pre-step-number">4</span>
                                <div>
                                    <h2 class="h4 fw-bold mb-0">Documentación</h2>
                                    <p class="text-muted mb-0">DNI y título o constancia son obligatorios para iniciar la revisión.</p>
                                </div>
                            </div>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="upload-card">
                                        <label class="form-label fw-bold"><i class="ti ti-id me-1"></i>DNI obligatorio</label>
                                        <input type="file" name="archivo_dni" class="form-control @error('archivo_dni') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png" required>
                                        <div class="small text-muted mt-2">Frente y dorso en un mismo archivo si es posible.</div>
                                        @error('archivo_dni')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="upload-card">
                                        <label class="form-label fw-bold"><i class="ti ti-certificate me-1"></i>Título o constancia obligatoria</label>
                                        <input type="file" name="archivo_titulo" class="form-control @error('archivo_titulo') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png" required>
                                        <div class="small text-muted mt-2">Título secundario o constancia correspondiente.</div>
                                        @error('archivo_titulo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="upload-card">
                                        <label class="form-label fw-bold"><i class="ti ti-file-text me-1"></i>Recibo opcional</label>
                                        <input type="file" name="archivo_recibo" class="form-control @error('archivo_recibo') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                                        <div class="small text-muted mt-2">Adjuntalo si corresponde a tu situación.</div>
                                        @error('archivo_recibo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="upload-card">
                                        <label class="form-label fw-bold"><i class="ti ti-paperclip me-1"></i>Archivo adicional</label>
                                        <input type="file" name="archivo_adicional" class="form-control @error('archivo_adicional') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                                        <div class="small text-muted mt-2">Certificados u otra documentación de respaldo.</div>
                                        @error('archivo_adicional')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="card-footer bg-white border-0 p-4 p-lg-5 pt-0">
                        <div class="cent-muted-box p-4 mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="confirmacion" required>
                                <label class="form-check-label" for="confirmacion">
                                    Declaro que los datos cargados son correctos y acepto que el CENT N°74 revise la documentación enviada para continuar el proceso de ingreso.
                                </label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
                            <p class="text-muted mb-0">Al finalizar vas a recibir un código y podrás descargar tu ficha PDF.</p>
                            <button class="btn btn-cent btn-lg px-5">
                                <i class="ti ti-send me-2"></i>Enviar preinscripción
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    const carreraSelect = document.getElementById('carrera_id');
    const sedeSelect = document.getElementById('cent_sede_id');
    const summaryCarrera = document.querySelector('[data-summary-carrera]');
    const summarySedes = document.querySelector('[data-summary-sedes]');

    function filtrarSedesPorCarrera() {
        if (!carreraSelect || !sedeSelect) return;

        const selected = carreraSelect.options[carreraSelect.selectedIndex];
        const raw = selected ? selected.dataset.sedes : '';
        const allowed = raw ? raw.split(',').filter(Boolean) : [];
        let visibleCount = 0;
        const visibleNames = [];

        sedeSelect.querySelectorAll('option[data-sede-option="1"]').forEach((option) => {
            const visible = allowed.length === 0 || allowed.includes(option.value);
            option.hidden = !visible;
            option.disabled = !visible;

            if (visible) {
                visibleCount++;
                visibleNames.push(option.textContent.trim());
            }
        });

        if (sedeSelect.selectedOptions[0]?.disabled) {
            sedeSelect.value = '';
        }

        sedeSelect.parentElement.querySelector('[data-sede-help]')?.remove();
        const help = document.createElement('div');
        help.className = 'small text-muted mt-2';
        help.dataset.sedeHelp = '1';
        help.textContent = carreraSelect.value
            ? `${visibleCount} sede${visibleCount === 1 ? '' : 's'} disponible${visibleCount === 1 ? '' : 's'} para esta carrera.`
            : 'Primero elegí una carrera para ver las sedes disponibles.';
        sedeSelect.insertAdjacentElement('afterend', help);

        if (summaryCarrera) {
            summaryCarrera.textContent = carreraSelect.value
                ? selected.textContent.trim()
                : 'Elegí una carrera para continuar.';
        }

        if (summarySedes) {
            summarySedes.textContent = visibleNames.length
                ? visibleNames.join(' · ')
                : 'Se mostrarán una vez que selecciones la carrera.';
        }
    }

    carreraSelect?.addEventListener('change', filtrarSedesPorCarrera);
    filtrarSedesPorCarrera();

    document.querySelectorAll('.upload-card input[type="file"]').forEach((input) => {
        const feedback = document.createElement('div');
        feedback.className = 'file-feedback';
        input.insertAdjacentElement('afterend', feedback);

        input.addEventListener('change', () => {
            const card = input.closest('.upload-card');
            const file = input.files && input.files[0];

            if (!file) {
                card.classList.remove('has-file');
                feedback.style.display = 'none';
                feedback.textContent = '';
                return;
            }

            const sizeMb = (file.size / 1024 / 1024).toFixed(2);
            card.classList.add('has-file');
            feedback.style.display = 'block';
            feedback.textContent = `Archivo seleccionado: ${file.name} (${sizeMb} MB)`;
        });
    });
</script>
@endpush
