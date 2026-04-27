<?php

namespace App\Filament\Widgets;

use App\Models\PreinscripcionCent;
use Filament\Facades\Filament;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class CentUltimasPreinscripciones extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return Filament::getCurrentPanel()?->getId() === 'cent';
    }

    protected function getTableQuery(): Builder
    {
        return PreinscripcionCent::query()
            ->with(['carrera', 'sede'])
            ->latest();
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Últimas preinscripciones')
            ->query($this->getTableQuery())
            ->columns([
                Tables\Columns\TextColumn::make('codigo')
                    ->label('Código')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('apellido_nombre')
                    ->label('Aspirante')
                    ->searchable(),
                Tables\Columns\TextColumn::make('carrera.name')
                    ->label('Carrera')
                    ->limit(34),
                Tables\Columns\TextColumn::make('sede.nombre')
                    ->label('Sede')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'en_revision' => 'En revisión',
                        'aprobada' => 'Aprobada',
                        'inscripta' => 'Inscripta',
                        'rechazada' => 'Rechazada',
                        default => 'Pendiente',
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'aprobada', 'inscripta' => 'success',
                        'rechazada' => 'danger',
                        'en_revision' => 'info',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('ver')
                    ->label('Abrir')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(fn (PreinscripcionCent $record): string => route('filament.cent.resources.preinscripciones-cent.edit', $record)),
            ])
            ->paginated([5, 10]);
    }
}
