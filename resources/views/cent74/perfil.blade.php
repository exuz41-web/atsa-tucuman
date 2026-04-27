@extends('layouts.cent')

@section('title', 'Mi perfil')
@section('header', 'Mi perfil')

@section('content')
<section class="portal-hero p-4 p-xl-5 mb-4">
    <span class="badge bg-white text-primary mb-3">Cuenta</span>
    <h1 class="display-6 fw-bolder mb-3">Datos personales</h1>
    <p class="fs-5 text-muted mb-0">Actualizá datos de contacto y foto. DNI, rol y sede se gestionan desde administración.</p>
</section>

<form method="POST" action="{{ route('cent.perfil.actualizar') }}" enctype="multipart/form-data" class="modern-card p-4">
    @csrf
    <div class="row g-4">
        <div class="col-md-6">
            <label class="form-label fw-bold">Nombre completo</label>
            <input name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">Teléfono</label>
            <input name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">Foto de perfil</label>
            <input type="file" name="foto_perfil" class="form-control" accept=".jpg,.jpeg,.png">
        </div>
        <div class="col-12">
            <label class="form-label fw-bold">Dirección</label>
            <input name="address" value="{{ old('address', $user->address) }}" class="form-control">
        </div>
        <div class="col-12">
            <div class="p-3 rounded-4 bg-light">
                <strong>DNI:</strong> {{ $user->dni ?: '-' }} ·
                <strong>Rol:</strong> {{ ucfirst($user->cent_role ?: $user->role) }} ·
                <strong>Sede:</strong> {{ $user->centSede->nombre ?: 'Sin sede asignada' }}
            </div>
        </div>
    </div>
    <div class="text-end mt-4">
        <button class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Guardar cambios</button>
    </div>
</form>
@endsection
