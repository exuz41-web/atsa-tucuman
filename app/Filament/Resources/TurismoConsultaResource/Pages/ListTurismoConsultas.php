<?php

namespace App\Filament\Resources\TurismoConsultaResource\Pages;

use App\Filament\Resources\TurismoConsultaResource;
use App\Models\TurismoConsulta;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListTurismoConsultas extends ListRecords
{
    protected static string $resource = TurismoConsultaResource::class;

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
                ->badge(TurismoConsulta::where('estado', 'pendiente')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('estado', 'pendiente')),

            'en_proceso' => Tab::make('En proceso')
                ->badge(TurismoConsulta::where('estado', 'en_proceso')->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('estado', 'en_proceso')),

            'respondidas' => Tab::make('Respondidas')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('estado', 'respondida')),

            'cerradas' => Tab::make('Cerradas')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('estado', 'cerrada')),
        ];
    }
}
