<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\TurismoConsulta;

class TurismoConsultaResource extends GenericResource
{
    protected static ?string $model = TurismoConsulta::class;

    protected static ?string $navigationIcon = 'heroicon-o-sun';

    protected static ?string $navigationGroup = 'Afiliados';

    protected static ?string $navigationLabel = 'Consultas de turismo';

    protected static ?string $slug = 'turismo-consultas';
}