<?php

namespace App\Filament\Resources\CentUserResource\Pages;

use App\Filament\Resources\CentUserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCentUser extends CreateRecord
{
    protected static string $resource = CentUserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['role'] ??= 'alumno';

        return $data;
    }
}

