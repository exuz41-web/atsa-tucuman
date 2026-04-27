@extends('layouts.app')

@section('title', 'ATSA Tucumán')

@section('content')
    <section class="bg-[#0f2236] px-5 py-24 text-center text-white">
        <h1 class="text-4xl font-black">ATSA Tucumán</h1>
        <p class="mx-auto mt-4 max-w-2xl text-slate-200">
            Sitio institucional de la Asociación de Trabajadores de la Sanidad Argentina, seccional Tucumán.
        </p>
        <a href="{{ route('home') }}" class="mt-8 inline-flex rounded-md bg-white px-6 py-3 text-sm font-black text-[#1e3a5f]">
            Ir al inicio
        </a>
    </section>
@endsection
