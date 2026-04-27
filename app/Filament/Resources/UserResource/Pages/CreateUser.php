<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Helpers\LogActividad;
use App\Support\AfiliacionSupport;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (blank($data['numero_afiliado'] ?? null)) {
            $data['numero_afiliado'] = AfiliacionSupport::nextNumeroAfiliado();
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        LogActividad::registrar('creo usuario', 'User', $this->record->id, $this->record->name);
    }
}

