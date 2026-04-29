<?php

namespace App\Filament\Pages;

use App\Helpers\LogActividad;
use App\Models\User;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class GestionCarnets extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'Afiliación y padrón';

    protected static ?string $navigationLabel = 'Carnets';

    protected static ?int $navigationSort = 50;

    protected static ?string $title = 'Gestión de carnets';

    protected static string $view = 'filament.pages.gestion-carnets';

    protected static ?string $slug = 'gestion-carnets';

    // Badge de navegación: carnets vencidos o por vencer en 30 días
    public static function getNavigationBadge(): ?string
    {
        $count = User::where('carnet_activo', true)
            ->whereNotNull('carnet_vencimiento')
            ->where('carnet_vencimiento', '<=', now()->addDays(30))
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => User::query()
                ->with('filial')
                ->whereIn('role', ['afiliado', 'admin'])
                ->whereNotNull('numero_afiliado'))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->description(fn (User $record): ?string => $record->lugar_trabajo),

                Tables\Columns\TextColumn::make('dni')
                    ->label('DNI')
                    ->searchable()
                    ->copyable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('numero_afiliado')
                    ->label('N° afiliado')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('carnet_estado')
                    ->label('Estado')
                    ->state(fn (User $record): string => $this->estado($record))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Activo'   => 'success',
                        'Vencido'  => 'warning',
                        'Inactivo' => 'danger',
                        default    => 'gray',
                    }),

                Tables\Columns\TextColumn::make('carnet_vencimiento')
                    ->label('Vencimiento')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('dias_para_vencer')
                    ->label('Días')
                    ->state(fn (User $record): string => $record->carnet_vencimiento
                        ? (string) now()->startOfDay()->diffInDays($record->carnet_vencimiento, false)
                        : '—')
                    ->badge()
                    ->color(fn (string $state): string => $state === '—' ? 'gray'
                        : ((int) $state < 0 ? 'danger' : ((int) $state <= 30 ? 'warning' : 'success')))
                    ->sortable(query: fn (Builder $q, string $direction) => $q->orderBy('carnet_vencimiento', $direction)),

                Tables\Columns\TextColumn::make('filial.name')
                    ->label('Filial')
                    ->placeholder('Central')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('carnet_emitido_at')
                    ->label('Emitido')
                    ->date('d/m/Y')
                    ->placeholder('Nunca')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('carnet_activo')
                    ->label('Activo'),

                Tables\Filters\Filter::make('por_vencer')
                    ->label('Por vencer (30 días)')
                    ->query(fn (Builder $q) => $q
                        ->where('carnet_activo', true)
                        ->whereNotNull('carnet_vencimiento')
                        ->where('carnet_vencimiento', '<=', now()->addDays(30))),

                Tables\Filters\Filter::make('vencidos')
                    ->label('Vencidos')
                    ->query(fn (Builder $q) => $q
                        ->where('carnet_activo', true)
                        ->whereNotNull('carnet_vencimiento')
                        ->where('carnet_vencimiento', '<', now()->startOfDay())),

                Tables\Filters\SelectFilter::make('filial')
                    ->relationship('filial', 'name')
                    ->label('Filial'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('activar')
                        ->label('Activar carnet')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (User $record): bool => ! $record->carnet_activo)
                        ->action(fn (User $record) => $this->actualizarCarnet($record, [
                            'carnet_activo'    => true,
                            'carnet_emitido_at' => now(),
                        ])),

                    Tables\Actions\Action::make('renovar')
                        ->label('Renovar 1 año')
                        ->icon('heroicon-o-arrow-path')
                        ->color('info')
                        ->action(fn (User $record) => $this->actualizarCarnet($record, [
                            'carnet_activo'      => true,
                            'carnet_emitido_at'  => now(),
                            'carnet_vencimiento' => now()->addYear()->endOfYear(),
                        ])),

                    Tables\Actions\Action::make('editarVencimiento')
                        ->label('Cambiar vencimiento')
                        ->icon('heroicon-o-calendar')
                        ->form([
                            Forms\Components\DatePicker::make('carnet_vencimiento')
                                ->label('Nueva fecha de vencimiento')
                                ->required()
                                ->minDate(now()),
                        ])
                        ->fillForm(fn (User $record): array => ['carnet_vencimiento' => $record->carnet_vencimiento])
                        ->action(fn (User $record, array $data) => $this->actualizarCarnet($record, $data)),

                    Tables\Actions\Action::make('desactivar')
                        ->label('Desactivar')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn (User $record): bool => (bool) $record->carnet_activo)
                        ->requiresConfirmation()
                        ->action(fn (User $record) => $this->actualizarCarnet($record, ['carnet_activo' => false])),

                    Tables\Actions\Action::make('verificar')
                        ->label('Ver QR público')
                        ->icon('heroicon-o-qr-code')
                        ->color('gray')
                        ->url(fn (User $record): string => route('carnet.verificar', $record->numero_afiliado))
                        ->openUrlInNewTab(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('renovar_masivo')
                    ->label('Renovar seleccionados (1 año)')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalHeading('Renovar carnets seleccionados')
                    ->modalDescription('Se renovarán todos los carnets seleccionados por 1 año a partir de hoy.')
                    ->action(function ($records): void {
                        $count = 0;
                        foreach ($records as $record) {
                            $this->actualizarCarnet($record, [
                                'carnet_activo'      => true,
                                'carnet_emitido_at'  => now(),
                                'carnet_vencimiento' => now()->addYear()->endOfYear(),
                            ], notify: false);
                            $count++;
                        }
                        Notification::make()
                            ->title("{$count} carnets renovados correctamente")
                            ->success()
                            ->send();
                    }),

                Tables\Actions\BulkAction::make('activar_masivo')
                    ->label('Activar seleccionados')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($records): void {
                        $count = 0;
                        foreach ($records as $record) {
                            $this->actualizarCarnet($record, [
                                'carnet_activo'    => true,
                                'carnet_emitido_at' => now(),
                            ], notify: false);
                            $count++;
                        }
                        Notification::make()
                            ->title("{$count} carnets activados")
                            ->success()
                            ->send();
                    }),

                Tables\Actions\DeleteBulkAction::make()->hidden(),
            ])
            ->defaultSort('carnet_vencimiento');
    }

    private function estado(User $record): string
    {
        if (! $record->carnet_activo) {
            return 'Inactivo';
        }

        if ($record->carnet_vencimiento && $record->carnet_vencimiento->lt(now()->startOfDay())) {
            return 'Vencido';
        }

        return 'Activo';
    }

    private function actualizarCarnet(User $record, array $data, bool $notify = true): void
    {
        $record->update($data);
        LogActividad::registrar('actualizo carnet', 'User', $record->id, $record->name);

        if ($notify) {
            Notification::make()
                ->title('Carnet actualizado — '.$record->name)
                ->success()
                ->send();
        }
    }
}
