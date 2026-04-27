<?php

namespace App\Filament\Resources\SolicitudAfiliacionResource\Pages;

use App\Filament\Resources\SolicitudAfiliacionResource;
use App\Models\SolicitudAfiliacion;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListSolicitudAfiliacions extends ListRecords
{
    protected static string $resource = SolicitudAfiliacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'todos' => Tab::make('Todos'),

            'pendientes' => Tab::make('Pendientes')
                ->badge(SolicitudAfiliacion::where('estado', 'pendiente')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('estado', 'pendiente')),

            'en_revision' => Tab::make('En revisión')
                ->badge(SolicitudAfiliacion::where('estado', 'en_revision')->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('estado', 'en_revision')),

            'observadas' => Tab::make('Observadas')
                ->badge(SolicitudAfiliacion::where('estado', 'observada')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('estado', 'observada')),

            'aprobadas' => Tab::make('Aprobadas')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('estado', 'aprobada')),

            'rechazadas' => Tab::make('Rechazadas')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('estado', 'rechazada')),
        ];
    }
}
