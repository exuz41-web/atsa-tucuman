<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $base = fn (): Builder => User::query()->where(fn (Builder $q) => $q->where('role', 'afiliado')->orWhereNotNull('numero_afiliado'));

        return [
            'todos' => Tab::make('Todos'),

            'activos' => Tab::make('Activos')
                ->badge($base()->where('estado_afiliado', 'activo')->orWhereNull('estado_afiliado')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $q) => $q->where(fn ($q) => $q->where('estado_afiliado', 'activo')->orWhereNull('estado_afiliado'))),

            'con_carnet' => Tab::make('Con carnet activo')
                ->badge($base()->where('carnet_activo', true)->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('carnet_activo', true)),

            'delegados' => Tab::make('Delegados')
                ->badge($base()->where('es_delegado_gremial', true)->count())
                ->modifyQueryUsing(fn (Builder $q) => $q->where('es_delegado_gremial', true)),

            'inactivos' => Tab::make('Inactivos / Baja')
                ->modifyQueryUsing(fn (Builder $q) => $q->whereIn('estado_afiliado', ['inactivo', 'suspendido', 'baja'])),
        ];
    }
}
