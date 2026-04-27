<?php

namespace App\Filament\Resources\OrdenPrestacionResource\Pages;

use App\Filament\Resources\OrdenPrestacionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrdenPrestacions extends ListRecords
{
    protected static string $resource = OrdenPrestacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('exportar_csv')
                ->label('Exportar CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(route('panel.reportes.atencion.ordenes'))
                ->openUrlInNewTab(),
            Actions\CreateAction::make(),
        ];
    }
}
