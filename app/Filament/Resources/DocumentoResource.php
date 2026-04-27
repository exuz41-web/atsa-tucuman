<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\Documento;

class DocumentoResource extends GenericResource
{
    protected static ?string $model = Documento::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Institución';

    protected static ?string $navigationLabel = 'Documentos';

    protected static ?string $slug = 'documentos';
}