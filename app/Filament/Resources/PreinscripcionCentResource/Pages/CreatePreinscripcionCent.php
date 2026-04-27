<?php

namespace App\Filament\Resources\PreinscripcionCentResource\Pages;

use App\Filament\Resources\PreinscripcionCentResource;
use Illuminate\Support\Str;
use Filament\Resources\Pages\CreateRecord;

class CreatePreinscripcionCent extends CreateRecord
{
    protected static string $resource = PreinscripcionCentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['codigo'] = $data['codigo'] ?? ('CENT-'.now()->format('Y').'-'.strtoupper(Str::random(6)));
        return $data;
    }
}

