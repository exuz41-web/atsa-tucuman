@extends('layouts.app')

@section('title', 'Efemérides | ATSA Tucumán')

@php
    $months = [1 => 'Mayo', 2 => 'Febrero'];
    $monthNames = [1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'];
    $fallback = collect([
        (object) ['dia' => 12, 'mes' => 5, 'titulo' => 'Día Internacional de la Enfermería', 'descripcion' => 'Reconocimiento a la labor esencial de enfermería.'],
        (object) ['dia' => 21, 'mes' => 5, 'titulo' => 'Día del Trabajador de la Salud', 'descripcion' => 'Jornada para valorar al sector sanitario.'],
        (object) ['dia' => 3, 'mes' => 7, 'titulo' => 'Día del Médico Rural', 'descripcion' => 'Homenaje al trabajo sanitario en comunidades rurales.'],
        (object) ['dia' => 29, 'mes' => 7, 'titulo' => 'Día del Médico', 'descripcion' => 'Fecha de reconocimiento profesional.'],
        (object) ['dia' => 20, 'mes' => 10, 'titulo' => 'Día del Pediatra', 'descripcion' => 'Saludo a quienes cuidan la salud infantil.'],
        (object) ['dia' => 21, 'mes' => 11, 'titulo' => 'Día Nacional de la Enfermería', 'descripcion' => 'Efeméride nacional del sector.'],
        (object) ['dia' => 3, 'mes' => 12, 'titulo' => 'Día del Médico', 'descripcion' => 'Reconocimiento a médicos y médicas.'],
    ]);
    $items = ($efemerides ?: collect())->count() ? $efemerides : $fallback;
@endphp

@section('content')
    {{-- HERO --}}
    <section class="atsa-page-hero py-14 position-relative"
             style="background-image: url('{{ asset('images/historia/formacion-cent-74.jpg') }}'); min-height: 400px;">
        <div class="container-fluid position-relative z-1">
            <div class="col-lg-7">
                <span class="badge bg-white text-primary fs-3 fw-bolder px-3 py-2 mb-4">
                    <i class="ti ti-calendar-event me-1"></i>CALENDARIO SANITARIO
                </span>
                <h1 class="fw-bolder display-4 text-white mb-3">Efemérides del sector salud</h1>
                <p class="fs-5 text-white text-opacity-75 mb-0 col-lg-9">
                    Fechas conmemorativas, homenajes y reconocimientos a los trabajadores
                    de la salud a lo largo del año.
                </p>
            </div>
        </div>
    </section>

    <section class="py-10 py-lg-14">
        <div class="container-fluid">
            @foreach ($items->groupBy('mes') as $mes => $group)
                <div class="mb-10">
                    <div class="d-flex align-items-center gap-3 mb-5">
                        <span class="rounded-2 px-4 py-2 fw-bolder fs-5 text-white" style="background:#1e3a5f;">
                            {{ $monthNames[(int) $mes] ?? $mes }}
                        </span>
                        <div class="flex-grow-1 border-top border-2" style="border-color:#e5eaef!important;"></div>
                    </div>
                    <div class="row">
                        @foreach ($group as $efemeride)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card border-0 rounded-3 p-5 h-100 efemeride-card">
                                    <div class="d-flex align-items-start gap-4">
                                        <div class="efemeride-day-badge">
                                            <span>{{ $efemeride->dia }}</span>
                                        </div>
                                        <div>
                                            <h3 class="fw-bolder fs-5 mb-2">{{ $efemeride->titulo }}</h3>
                                            <p class="fs-4 text-muted mb-0">{{ $efemeride->descripcion }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    @push('styles')
    <style>
        .efemeride-card {
            box-shadow: 0 8px 24px rgba(42,53,71,.07);
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .efemeride-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 38px rgba(42,53,71,.12);
        }
        .efemeride-day-badge {
            min-width: 56px;
            height: 56px;
            border-radius: 12px;
            background: #ecf2ff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .efemeride-day-badge span {
            font-size: 1.6rem;
            font-weight: 900;
            color: #1e3a5f;
            line-height: 1;
        }
    </style>
    @endpush
@endsection
