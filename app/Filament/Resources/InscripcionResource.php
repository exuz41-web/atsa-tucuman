<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\Inscripcion;

class InscripcionResource extends GenericResource
{
    protected static ?string $model = Inscripcion::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Gestión académica';

    protected static ?string $navigationLabel = 'Inscripciones académicas';

    protected static ?int $navigationSort = 50;

    protected static ?string $slug = 'inscripciones-academicas';

    protected static ?string $panelScope = 'cent';
}