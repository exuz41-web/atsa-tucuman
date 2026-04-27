@extends('layouts.afiliado')

@section('title', 'Nueva solicitud')
@section('page_title', 'Nueva solicitud')

@section('content')
<form method="POST" action="{{ route('afiliados.pedidos.guardar') }}" enctype="multipart/form-data" data-pedido-form>
    @csrf

    <div class="portal-card p-4 p-lg-5 mb-4">
        <div class="d-flex align-items-center gap-3 mb-4">
            <span class="portal-icon"><i class="ti ti-circle-plus"></i></span>
            <div>
                <p class="text-primary fw-semibold fs-3 mb-1">NUEVA SOLICITUD</p>
                <h2 class="fw-bolder mb-0">Cargar pedido</h2>
            </div>
        </div>

        <div class="row g-3">
            @foreach ([['1', 'Datos del pedido', 'ti-edit'], ['2', 'Documentación', 'ti-paperclip'], ['3', 'Confirmación', 'ti-send']] as [$number, $label, $icon])
                <div class="col-md-4">
                    <div class="p-3 rounded-3 border bg-light h-100">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-primary rounded-circle d-inline-grid" style="width:34px;height:34px;place-items:center;">{{ $number }}</span>
                            <div>
                                <p class="fw-bolder mb-0">{{ $label }}</p>
                                <p class="text-muted fs-2 mb-0"><i class="ti {{ $icon }} me-1"></i>Completar</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="portal-card p-4 p-lg-5 h-100">
                <p class="text-primary fw-semibold fs-3 mb-1">PASO 1</p>
                <h3 class="fw-bolder mb-4">Contanos qué necesitás</h3>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Tipo de pedido</label>
                    <select name="tipo" class="form-select" required data-required-field>
                        <option value="">Seleccionar</option>
                        <option value="anteojos">Anteojos</option>
                        <option value="protesis">Prótesis</option>
                        <option value="medicamentos">Medicamentos</option>
                        <option value="ayuda_economica">Ayuda económica</option>
                        <option value="otro">Otro</option>
                    </select>
                    @error('tipo') <div class="text-danger mt-1 fs-2 fw-semibold">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="form-label fw-semibold">Descripción del pedido</label>
                    <textarea name="descripcion" rows="7" class="form-control" required minlength="10" data-required-field placeholder="Describí tu pedido con la mayor claridad posible">{{ old('descripcion') }}</textarea>
                    <p class="text-muted fs-2 mt-2 mb-0" data-description-help>Escribí al menos 10 caracteres.</p>
                    @error('descripcion') <div class="text-danger mt-1 fs-2 fw-semibold">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="portal-card p-4 p-lg-5 h-100">
                <p class="text-primary fw-semibold fs-3 mb-1">PASO 2</p>
                <h3 class="fw-bolder mb-2">Adjuntá documentación</h3>
                <p class="text-muted mb-4">Podés subir PDF o imagen. Tamaño máximo: 5 MB por archivo.</p>

                <div class="vstack gap-3">
                    @foreach ([
                        ['archivo_dni', 'DNI', true],
                        ['archivo_recibo', 'Recibo de sueldo', true],
                        ['archivo_adicional', 'Archivo adicional', false],
                    ] as [$name, $label, $required])
                        <label class="d-block rounded-3 border bg-light p-3 cursor-pointer">
                            <div class="d-flex align-items-center gap-3">
                                <span class="portal-icon" style="width:44px;height:44px;font-size:20px;"><i class="ti ti-upload"></i></span>
                                <div class="min-w-0">
                                    <p class="fw-bolder mb-1">{{ $label }} {{ $required ? '*' : '' }}</p>
                                    <p class="text-muted fs-2 mb-0 text-truncate" data-file-preview="{{ $name }}">Seleccionar archivo</p>
                                </div>
                            </div>
                            <input type="file" name="{{ $name }}" class="d-none" accept=".pdf,.jpg,.jpeg,.png,.webp" {{ $required ? 'required data-required-file' : '' }} data-file-input="{{ $name }}">
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="portal-card p-4 p-lg-5">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div>
                        <p class="text-primary fw-semibold fs-3 mb-1">PASO 3</p>
                        <h3 class="fw-bolder mb-1">Confirmación y envío</h3>
                        <p class="text-muted mb-0" data-form-status>Completá los campos obligatorios para enviar.</p>
                    </div>
                    <button class="btn btn-primary px-5 py-3 shadow-none" data-submit-button disabled>
                        <i class="ti ti-send me-2"></i>Enviar solicitud
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    (() => {
        const form = document.querySelector('[data-pedido-form]');
        if (!form) return;

        const submit = form.querySelector('[data-submit-button]');
        const status = form.querySelector('[data-form-status]');
        const requiredFields = [...form.querySelectorAll('[data-required-field]')];
        const requiredFiles = [...form.querySelectorAll('[data-required-file]')];

        const validate = () => {
            const fieldsOk = requiredFields.every((field) => field.value.trim().length >= (field.getAttribute('minlength') || 1));
            const filesOk = requiredFiles.every((field) => field.files.length > 0);
            const ok = fieldsOk && filesOk;
            submit.disabled = !ok;
            status.textContent = ok ? 'Todo listo para enviar.' : 'Completá los campos obligatorios para enviar.';
            status.className = ok ? 'text-success fw-semibold mb-0' : 'text-muted mb-0';
        };

        form.querySelectorAll('[data-file-input]').forEach((input) => {
            input.addEventListener('change', () => {
                const preview = form.querySelector(`[data-file-preview="${input.dataset.fileInput}"]`);
                preview.textContent = input.files.length ? input.files[0].name : 'Seleccionar archivo';
                validate();
            });
        });

        requiredFields.forEach((field) => field.addEventListener('input', validate));
        validate();
    })();
</script>
@endpush
