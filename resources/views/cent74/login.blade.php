@extends('layouts.cent-public')

@section('title', 'Ingreso Portal CENT N°74')

@push('styles')
<style>
    .cent-login-hero {
        position: relative;
        overflow: hidden;
        border-radius: 28px;
        padding: 42px;
        background:
            radial-gradient(circle at top right, rgba(73, 190, 255, .24), transparent 24%),
            linear-gradient(135deg, #102338 0%, #1e3a5f 60%, #2560a0 100%);
        color: #fff;
        box-shadow: 0 22px 56px rgba(16, 35, 56, .18);
    }

    .cent-login-hero .text-muted {
        color: rgba(255, 255, 255, .76) !important;
    }

    .cent-access-tile {
        border-radius: 18px;
        background: rgba(255, 255, 255, .08);
        border: 1px solid rgba(255, 255, 255, .12);
        padding: 16px;
        height: 100%;
    }

    .cent-login-card {
        border: 0;
        border-radius: 28px;
        box-shadow: 0 20px 52px rgba(42, 53, 71, .12);
        overflow: hidden;
    }

    .cent-login-head {
        background: linear-gradient(180deg, rgba(30, 58, 95, .04), rgba(73, 190, 255, .08));
        border-bottom: 1px solid #e5eaef;
    }

    .password-wrap {
        position: relative;
    }

    .password-wrap .form-control {
        padding-right: 52px;
    }

    .password-toggle {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        width: 40px;
        height: 40px;
        border: 0;
        border-radius: 12px;
        background: transparent;
        color: #5a6a85;
        display: grid;
        place-items: center;
    }
</style>
@endpush

@section('content')
<section class="min-vh-100 d-flex align-items-center bg-light">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="cent-login-hero">
                    <span class="section-badge bg-white text-primary">Portal académico</span>
                    <h1 class="display-5 fw-bolder text-white">Ingreso para alumnos, docentes y directivos</h1>
                    <p class="fs-5 text-muted mb-4">Acceso exclusivo para usuarios ya habilitados por la institución. Desde acá podés seguir tu cursado, consultar avisos, gestionar actas y operar el aula virtual.</p>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="cent-access-tile">
                                <div class="fw-bold mb-1">Alumnos</div>
                                <div class="small text-muted">Notas, legajo, cuotas, permisos, aula y carnet.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="cent-access-tile">
                                <div class="fw-bold mb-1">Docentes</div>
                                <div class="small text-muted">Comisiones, materiales, asistencia, planillas y mesas.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="cent-access-tile">
                                <div class="fw-bold mb-1">Directivos</div>
                                <div class="small text-muted">Seguimiento académico, reportes y control institucional.</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 flex-wrap mt-4">
                        <a href="{{ route('cent.preinscripcion') }}" class="btn btn-light">Preinscribirme</a>
                        <a href="{{ route('cent.preinscripcion.consulta') }}" class="btn btn-outline-light">Consultar preinscripción</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 offset-lg-1">
                <div class="card cent-login-card">
                    <div class="card-body p-4 p-lg-5 cent-login-head">
                        <a href="{{ route('cent.index') }}" class="cent-brand mb-4">
                            <span class="cent-mark">74</span>
                            <span>
                                <strong>CENT N°74</strong>
                                <span>Portal académico</span>
                            </span>
                        </a>
                        <h2 class="fw-bold">Iniciar sesión</h2>
                        <p class="text-muted mb-0">Usá tu email, DNI o legajo académico para ingresar.</p>
                    </div>
                    <div class="card-body p-4 p-lg-5">
                        @if($errors->any())
                            <div class="alert alert-danger rounded-4">{{ $errors->first() }}</div>
                        @endif
                        <form action="{{ route('cent.login.submit') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email, DNI o legajo académico</label>
                                <input name="identificador" value="{{ old('identificador') }}" class="form-control form-control-lg" required autofocus placeholder="Ej. alumno@cent74.edu.ar o 12345678">
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold">Contraseña</label>
                                <div class="password-wrap">
                                    <input id="cent-password" type="password" name="password" class="form-control form-control-lg" required>
                                    <button class="password-toggle" type="button" data-toggle-password="#cent-password" aria-label="Ver contraseña">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="cent-muted-box p-3 mb-4">
                                <div class="small text-muted">
                                    Si todavía no tenés usuario institucional, primero completá la preinscripción y esperá la habilitación administrativa correspondiente.
                                </div>
                            </div>
                            <button class="btn btn-cent btn-lg w-100">Entrar al portal</button>
                            <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mt-3">
                                <a href="{{ route('cent.index') }}" class="small text-decoration-none">Volver al sitio CENT</a>
                                <a href="{{ route('cent.preinscripcion') }}" class="small text-decoration-none">Nueva preinscripción</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    document.querySelectorAll('[data-toggle-password]').forEach((button) => {
        button.addEventListener('click', () => {
            const input = document.querySelector(button.dataset.togglePassword);
            const icon = button.querySelector('i');
            if (!input) return;

            const visible = input.type === 'text';
            input.type = visible ? 'password' : 'text';
            button.setAttribute('aria-label', visible ? 'Ver contraseña' : 'Ocultar contraseña');
            icon?.classList.toggle('ti-eye', visible);
            icon?.classList.toggle('ti-eye-off', !visible);
        });
    });
</script>
@endsection
