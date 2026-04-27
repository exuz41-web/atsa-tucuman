<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\SolicitudAfiliacion;

class SolicitudAfiliacionResource extends GenericResource
{
    protected static ?string $model = SolicitudAfiliacion::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $navigationGroup = 'Afiliados';

    protected static ?string $navigationLabel = 'Solicitudes de afiliación';

    protected static ?string $slug = 'solicitudes-afiliacion';
}