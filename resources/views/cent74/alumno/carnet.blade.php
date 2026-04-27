@extends('layouts.cent')

@section('title', 'Carnet de estudiante')
@section('header', 'Carnet de estudiante')

@section('content')
<div class="row g-4 align-items-start">
    <div class="col-xl-8">
        <div class="modern-card p-4 p-lg-5">
            <div class="student-card mx-auto">
                <div class="student-card-bg"></div>
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <div class="fw-black fs-4">CENT N°74</div>
                        <div class="small text-primary fw-bold">CREDENCIAL DE ESTUDIANTE</div>
                    </div>
                    <span class="badge bg-primary text-white">ALUMNO REGULAR</span>
                </div>
                <div class="row g-4 align-items-center">
                    <div class="col-sm-4 text-center">
                        @if($alumno->foto_perfil)
                            <img src="{{ asset('storage/'.$alumno->foto_perfil) }}" class="student-photo" alt="{{ $alumno->name }}">
                        @else
                            <div class="student-photo placeholder-photo">{{ mb_substr($alumno->name, 0, 1) }}</div>
                        @endif
                    </div>
                    <div class="col-sm-5">
                        <h2 class="fw-bolder mb-1">{{ $alumno->name }}</h2>
                        <div class="text-muted mb-3">DNI {{ $alumno->dni ?: 'No registrado' }}</div>
                        <div class="small text-muted">Carrera</div>
                        <div class="fw-bold">{{ $matricula?->carrera?->name ?: 'Sin matrícula' }}</div>
                        <div class="small text-muted mt-2">Sede</div>
                        <div class="fw-bold">{{ $matricula?->sede?->nombre ?: 'CENT N°74' }}</div>
                    </div>
                    <div class="col-sm-3 text-center">
                        <img src="data:image/png;base64,{{ $qrCode }}" class="img-fluid rounded-3 bg-white p-2" alt="QR">
                        <div class="small text-muted mt-2">Verificar</div>
                    </div>
                </div>
                <div class="student-card-footer">Centro Educativo de Nivel Terciario N°74</div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="modern-card p-4">
            <h3 class="fw-bolder mb-3">Acciones</h3>
            <a href="{{ route('cent.alumno.carnet.pdf') }}" class="btn btn-primary w-100 mb-3"><i class="ti ti-download me-1"></i> Descargar PDF</a>
            <a href="{{ route('cent.perfil') }}" class="btn btn-outline-primary w-100"><i class="ti ti-camera me-1"></i> Actualizar foto</a>
            <hr>
            <p class="text-muted mb-0">El QR permite validar públicamente que el estudiante pertenece al CENT N°74.</p>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .student-card {
        max-width: 760px;
        min-height: 430px;
        border-radius: 28px;
        background: #ffffff;
        border: 1px solid #e5eaef;
        box-shadow: 0 24px 70px rgba(30, 58, 95, .14);
        padding: 34px;
        position: relative;
        overflow: hidden;
    }
    .student-card-bg {
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 999px;
        background: #ecf2ff;
        right: -90px;
        top: -90px;
        z-index: 0;
    }
    .student-card > *:not(.student-card-bg) { position: relative; z-index: 1; }
    .student-photo {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 8px solid #ecf2ff;
    }
    .placeholder-photo {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #1e3a5f;
        color: #fff;
        font-size: 48px;
        font-weight: 900;
    }
    .student-card-footer {
        margin: 28px -34px -34px;
        padding: 18px;
        background: linear-gradient(90deg, #1e3a5f, #49beff);
        color: #fff;
        text-align: center;
        font-weight: 900;
        letter-spacing: .08em;
        text-transform: uppercase;
    }
</style>
@endpush
