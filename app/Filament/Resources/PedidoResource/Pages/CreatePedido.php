<?php

namespace App\Filament\Resources\PedidoResource\Pages;

use App\Filament\Resources\PedidoResource;
use App\Helpers\LogActividad;
use Filament\Resources\Pages\CreateRecord;

class CreatePedido extends CreateRecord
{
    protected static string $resource = PedidoResource::class;

    protected function afterCreate(): void
    {
        LogActividad::registrar('creo pedido', 'Pedido', $this->record->id, PedidoResource::numero($this->record->id));
    }
}
