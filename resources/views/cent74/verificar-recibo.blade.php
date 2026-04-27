@extends('layouts.cent-public')

@section('title', 'Verificación de recibo')

@section('content')
<section class="py-5 bg-light">
    <div class="container py-lg-5">
        <div class="card cent-card mx-auto" style="max-width: 720px;">
            <div class="card-body p-5 text-center">
                @if($recibo)
                    <span class="feature-icon mx-auto mb-4 bg-success-subtle text-success"><i class="ti ti-receipt"></i></span>
                    <h1 class="fw-bolder">Recibo válido</h1>
                    <p class="text-muted">{{ $recibo->numero }}</p>
                    <div class="bg-light rounded-4 p-4 text-start">
                        Alumno: <strong>{{ $recibo->alumno->name }}</strong><br>
                        Concepto: <strong>{{ $recibo->concepto }}</strong><br>
                        Monto: <strong>${{ number_format($recibo->monto, 2, ',', '.') }}</strong><br>
                        Emitido: <strong>{{ $recibo->emitido_at?->format('d/m/Y H:i') }}</strong>
                    </div>
                @else
                    <span class="feature-icon mx-auto mb-4 bg-danger-subtle text-danger"><i class="ti ti-x"></i></span>
                    <h1 class="fw-bolder">Recibo no encontrado</h1>
                    <p class="text-muted mb-0">El código escaneado no corresponde a un recibo emitido por el CENT N°74.</p>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
