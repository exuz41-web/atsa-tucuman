@extends('layouts.cent-public')

@section('title', 'Consultar preinscripción CENT N°74')
@section('meta_description', 'Consulta el estado de tu preinscripción al CENT N°74 con código y DNI.')

@section('content')
<section class="py-5 bg-light">
    <div class="container py-lg-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-4">
                    <span class="section-badge bg-primary-subtle text-primary">
                        <i class="ti ti-search"></i> Seguimiento online
                    </span>
                    <h1 class="display-5 fw-bolder">Consultar preinscripción</h1>
                    <p class="fs-5 text-muted mb-0">
                        Ingresá el código recibido y tu DNI para ver el estado de la solicitud o actualizar documentación.
                    </p>
                </div>

                @if(session('status'))
                    <div class="alert alert-success rounded-4">{{ session('status') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger rounded-4">
                        No encontramos una preinscripción con esos datos o hay campos pendientes.
                    </div>
                @endif

                <form action="{{ route('cent.preinscripcion.consultar') }}" method="POST" class="card cent-card">
                    @csrf
                    <div class="card-body p-4 p-lg-5">
                        <div class="row g-4">
                            <div class="col-md-7">
                                <label class="form-label fw-bold">Código de preinscripción</label>
                                <input name="codigo" value="{{ old('codigo') }}" class="form-control form-control-lg @error('codigo') is-invalid @enderror" placeholder="CENT-2026-ABC123" required>
                                @error('codigo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-bold">DNI</label>
                                <input name="dni" value="{{ old('dni') }}" class="form-control form-control-lg @error('dni') is-invalid @enderror" placeholder="Sin puntos" required>
                                @error('dni')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="cent-muted-box p-4 mt-4">
                            <div class="d-flex gap-3">
                                <i class="ti ti-shield-check text-primary fs-6"></i>
                                <p class="text-muted mb-0">
                                    Por seguridad, el código debe coincidir con el DNI informado al momento de la preinscripción.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 p-4 p-lg-5 pt-0 text-end">
                        <button class="btn btn-cent btn-lg px-5">
                            <i class="ti ti-search me-2"></i>Consultar estado
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
