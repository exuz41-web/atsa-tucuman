@extends('layouts.afiliado')

@section('title', 'Solicitar beneficio')
@section('page_title', 'Solicitar beneficio')

@section('content')
    <form method="POST" action="{{ route('afiliados.beneficios.guardar', $beneficio) }}" enctype="multipart/form-data" data-benefit-request-form>
        @csrf

        <div class="portal-card p-4 p-lg-5 mb-4">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <p class="text-primary fw-semibold fs-3 mb-1">SOLICITUD DE BENEFICIO</p>
                    <h2 class="fw-bolder mb-2">{{ $beneficio->titulo }}</h2>
                    <p class="text-muted mb-0">{{ $beneficio->descripcion_corta }}</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('afiliados.beneficios') }}" class="btn btn-outline-primary shadow-none">
                        <i class="ti ti-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="portal-card p-4 p-lg-5 h-100">
                    <h3 class="fw-bolder mb-3">Contanos qué necesitás</h3>
                    <p class="text-muted mb-4">Tu mensaje ayuda al equipo de ATSA a revisar el caso y responder con mayor precisión.</p>
                    <textarea name="mensaje" rows="9" class="form-control" required minlength="10" maxlength="2000" data-required-field placeholder="Describí tu solicitud, situación o consulta sobre este beneficio.">{{ old('mensaje') }}</textarea>
                    <p class="text-muted fs-2 mt-2 mb-0" data-form-status>Escribí al menos 10 caracteres.</p>
                    @error('mensaje') <div class="text-danger mt-2 fs-2 fw-semibold">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="col-lg-5">
                <div class="portal-card p-4 p-lg-5 h-100">
                    <h3 class="fw-bolder mb-2">Adjuntar documentación</h3>
                    <p class="text-muted mb-4">Podés subir PDF o imagen. Máximo 5 MB por archivo.</p>

                    <div class="vstack gap-3">
                        @foreach ([
                            ['archivo_dni', 'DNI'],
                            ['archivo_recibo', 'Recibo de sueldo'],
                            ['archivo_adicional', 'Archivo adicional'],
                        ] as [$name, $label])
                            <label class="d-block rounded-3 border bg-light p-3 cursor-pointer">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="portal-icon" style="width:44px;height:44px;font-size:20px;"><i class="ti ti-upload"></i></span>
                                    <div class="min-w-0">
                                        <p class="fw-bolder mb-1">{{ $label }}</p>
                                        <p class="text-muted fs-2 mb-0 text-truncate" data-file-preview="{{ $name }}">Seleccionar archivo</p>
                                    </div>
                                </div>
                                <input type="file" name="{{ $name }}" class="d-none" accept=".pdf,.jpg,.jpeg,.png,.webp" data-file-input="{{ $name }}">
                            </label>
                            @error($name) <div class="text-danger fs-2 fw-semibold">{{ $message }}</div> @enderror
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="portal-card p-4 h-100">
                    <h4 class="fw-bolder mb-3">Requisitos</h4>
                    <p class="text-muted mb-4">{{ $beneficio->requisitos ?: 'El equipo de ATSA informará los requisitos específicos al revisar tu solicitud.' }}</p>
                    <h4 class="fw-bolder mb-3">Documentación sugerida</h4>
                    <p class="text-muted mb-0">{{ $beneficio->documentacion ?: 'DNI, recibo de sueldo y toda documentación que ayude a evaluar el beneficio.' }}</p>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="portal-card p-4 h-100 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div>
                        <h4 class="fw-bolder mb-1">Enviar solicitud</h4>
                        <p class="text-muted mb-0">Vas a poder ver la respuesta en tu portal de afiliado.</p>
                    </div>
                    <button class="btn btn-primary px-5 py-3 shadow-none" data-submit-button disabled>
                        <i class="ti ti-send me-2"></i>Enviar solicitud
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    (() => {
        const form = document.querySelector('[data-benefit-request-form]');
        if (!form) return;

        const textarea = form.querySelector('[data-required-field]');
        const submit = form.querySelector('[data-submit-button]');
        const status = form.querySelector('[data-form-status]');

        const validate = () => {
            const ok = textarea.value.trim().length >= 10;
            submit.disabled = !ok;
            status.textContent = ok ? 'Todo listo para enviar.' : 'Escribí al menos 10 caracteres.';
            status.className = ok ? 'text-success fw-semibold fs-2 mt-2 mb-0' : 'text-muted fs-2 mt-2 mb-0';
        };

        form.querySelectorAll('[data-file-input]').forEach((input) => {
            input.addEventListener('change', () => {
                const preview = form.querySelector(`[data-file-preview="${input.dataset.fileInput}"]`);
                preview.textContent = input.files.length ? input.files[0].name : 'Seleccionar archivo';
            });
        });

        textarea.addEventListener('input', validate);
        validate();
    })();
</script>
@endpush
