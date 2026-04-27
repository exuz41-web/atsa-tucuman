<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\SolicitudBeneficio;

class SolicitudBeneficioResource extends GenericResource
{
    protected static ?string $model = SolicitudBeneficio::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationGroup = 'Afiliados';

    protected static ?string $navigationLabel = 'Solicitudes de beneficios';

    protected static ?string $slug = 'solicitudes-beneficios';
}