@extends('layouts.afiliado')

@section('title', 'Mis Datos')
@section('page_title', 'Mis datos')

@php
    $fotoUrl = \App\Support\CarnetSupport::fotoUrl($user);
    $iniciales = \App\Support\CarnetSupport::initials($user->name);
@endphp

@section('content')
<div class="row g-4">
    <div class="col-xl-4">
        <div class="portal-card p-4 h-100 d-flex flex-column gap-4">

            {{-- Foto de perfil --}}
            <div class="text-center">
                <div class="d-inline-grid overflow-hidden rounded-circle mb-3"
                     style="width:110px;height:110px;place-items:center;background:#ecf2ff;color:#1e3a5f;font-size:32px;font-weight:900;border:4px solid #dbe5f0;">
                    @if($fotoUrl)
                        <img src="{{ $fotoUrl }}" alt="Foto de {{ $user->name }}"
                             class="w-100 h-100" style="object-fit:cover;">
                    @else
                        {{ $iniciales }}
                    @endif
                </div>
                <h3 class="fw-bolder mb-1">{{ $user->name }}</h3>
                @if($user->numero_afiliado)
                <span class="badge px-3 py-2 fw-bold" style="background:#ecf2ff;color:#1e3a5f;">
                    N° {{ $user->numero_afiliado }}
                </span>
                @endif
            </div>

            {{-- Datos de carnet --}}
            <div class="vstack gap-3">
                <div class="p-3 rounded-3 bg-light">
                    <p class="text-muted mb-1 fw-semibold fs-2">Número de afiliado</p>
                    <h6 class="fw-bolder mb-0">{{ $user->numero_afiliado ?: 'Sin emitir' }}</h6>
                </div>
                <div class="p-3 rounded-3 bg-light">
                    <p class="text-muted mb-1 fw-semibold fs-2">DNI</p>
                    <h6 class="fw-bolder mb-0">{{ $user->dni ?: 'No registrado' }}</h6>
                </div>
                <div class="p-3 rounded-3 bg-light">
                    <p class="text-muted mb-1 fw-semibold fs-2">Filial</p>
                    <h6 class="fw-bolder mb-0">{{ $user->filial?->name ?: 'Sede Central' }}</h6>
                </div>
                @if($user->lugar_trabajo)
                <div class="p-3 rounded-3 bg-light">
                    <p class="text-muted mb-1 fw-semibold fs-2">Lugar de trabajo</p>
                    <h6 class="fw-bolder mb-0">{{ $user->lugar_trabajo }}</h6>
                </div>
                @endif
            </div>

            {{-- Actualizar foto --}}
            <div class="border-top pt-4">
                <p class="fw-bolder text-dark mb-3"><i class="ti ti-camera me-2 text-primary"></i>Actualizar foto de perfil</p>
                <form method="POST" action="{{ route('afiliado.carnet.foto') }}" enctype="multipart/form-data" class="vstack gap-3">
                    @csrf
                    <input type="file" name="foto" accept="image/png,image/jpeg" required class="form-control">
                    @error('foto') <p class="text-danger fw-bold fs-2 mb-0">{{ $message }}</p> @enderror
                    <p class="text-muted fs-2 mb-0">JPG o PNG, máx. 2MB. Rostro visible y fondo neutro.</p>
                    <button class="btn btn-primary shadow-none w-100">
                        <i class="ti ti-device-floppy me-2"></i>Guardar foto
                    </button>
                </form>
            </div>

        </div>
    </div>

    <div class="col-xl-8">
        <div class="portal-card p-4 p-lg-5">
            <div class="d-flex align-items-center gap-3 mb-4">
                <span class="portal-icon"><i class="ti ti-edit"></i></span>
                <div>
                    <p class="text-primary fw-semibold fs-3 mb-1">DATOS EDITABLES</p>
                    <h2 class="fw-bolder mb-0">Actualizar información personal</h2>
                </div>
            </div>

            <form method="POST" action="{{ route('afiliados.datos.actualizar') }}">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold">Nombre completo</label>
                        <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $user->name) }}" required>
                        @error('name') <div class="text-danger mt-1 fs-2 fw-semibold">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label fw-semibold">Correo electrónico</label>
                        <input type="email" name="email" class="form-control" id="email" value="{{ old('email', $user->email) }}" required>
                        @error('email') <div class="text-danger mt-1 fs-2 fw-semibold">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label fw-semibold">Teléfono</label>
                        <input type="text" name="phone" class="form-control" id="phone" value="{{ old('phone', $user->phone) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="address" class="form-label fw-semibold">Dirección</label>
                        <input type="text" name="address" class="form-control" id="address" value="{{ old('address', $user->address) }}">
                    </div>
                </div>

                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mt-5">
                    <p class="text-muted mb-0">Los datos sindicales sensibles solo pueden modificarse desde administración.</p>
                    <button type="submit" class="btn btn-primary px-5 py-3 shadow-none">
                        <i class="ti ti-device-floppy me-2"></i>Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
