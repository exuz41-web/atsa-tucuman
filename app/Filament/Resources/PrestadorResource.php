<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\PrestadorResource\Pages;
use App\Models\Prestador;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PrestadorResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = Prestador::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Atención al afiliado';

    protected static ?string $navigationLabel = 'Prestadores';

    protected static ?string $modelLabel = 'prestador';

    protected static ?string $pluralModelLabel = 'prestadores';

    protected static ?string $slug = 'prestadores';

    protected static ?int $navigationSort = 6;

    protected static ?string $panelScope = 'admin';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos del prestador')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('nombre')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Forms\Components\Select::make('tipo')
                        ->label('Tipo')
                        ->options(Prestador::tipos())
                        ->required()
                        ->native(false),

                    Forms\Components\TextInput::make('cuit')
                        ->label('CUIT')
                        ->maxLength(30),

                    Forms\Components\TextInput::make('responsable')
                        ->label('Responsable')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('telefono')
                        ->label('Teléfono')
                        ->tel()
                        ->maxLength(80),

                    Forms\Components\TextInput::make('localidad')
                        ->label('Localidad')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('provincia')
                        ->label('Provincia')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('direccion')
                        ->label('Dirección')
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('observaciones')
                        ->label('Observaciones internas')
                        ->rows(4)
                        ->columnSpanFull(),

                    Forms\Components\Toggle::make('activo')
                        ->label('Activo')
                        ->default(true)
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Acceso al portal')
                ->columns(1)
                ->visible(fn (?Prestador $record): bool => filled($record?->portal_token))
                ->schema([
                    Forms\Components\TextInput::make('portal_url')
                        ->label('Link privado del prestador')
                        ->formatStateUsing(fn (?string $state, ?Prestador $record): string => $record?->portalUrl() ?? '')
                        ->disabled()
                        ->dehydrated(false)
                        ->helperText('Compartir este enlace solo con el prestador. Si se filtra, usar la acción Regenerar acceso.'),

                    Forms\Components\TextInput::make('portal_token')
                        ->label('Token')
                        ->disabled()
                        ->dehydrated(false),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('nombre')
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('tipo')
                    ->label('Tipo')
                    ->formatStateUsing(fn (?string $state): string => Prestador::tipos()[$state] ?? ucfirst((string) $state))
                    ->color('info'),

                Tables\Columns\TextColumn::make('responsable')
                    ->label('Responsable')
                    ->placeholder('—')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('localidad')
                    ->label('Localidad')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('ordenesPrestacion_count')
                    ->label('Órdenes')
                    ->counts('ordenesPrestacion')
                    ->sortable(),

                Tables\Columns\TextColumn::make('portal_token')
                    ->label('Portal')
                    ->formatStateUsing(fn (?string $state, Prestador $record): string => $record->portalUrl())
                    ->url(fn (Prestador $record): string => $record->portalUrl(), true)
                    ->limit(34)
                    ->copyable()
                    ->copyMessage('Link copiado')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('activo')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo')
                    ->label('Tipo')
                    ->options(Prestador::tipos()),
                Tables\Filters\TernaryFilter::make('activo')
                    ->label('Activo'),
            ])
            ->actions([
                Tables\Actions\Action::make('ver_portal')
                    ->label('Ver portal')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('info')
                    ->url(fn (Prestador $record): string => $record->portalUrl(), true),

                Tables\Actions\Action::make('regenerar_token')
                    ->label('Regenerar acceso')
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Regenerar acceso del prestador')
                    ->modalDescription('El enlace anterior dejará de funcionar. Compartí el nuevo link privado con el prestador.')
                    ->action(function (Prestador $record): void {
                        $record->update(['portal_token' => (string) Str::uuid()]);

                        Notification::make()
                            ->title('Acceso regenerado')
                            ->body('Nuevo link: '.$record->fresh()->portalUrl())
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('toggle_activo')
                    ->label(fn (Prestador $record): string => $record->activo ? 'Desactivar' : 'Activar')
                    ->icon(fn (Prestador $record): string => $record->activo ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                    ->color(fn (Prestador $record): string => $record->activo ? 'gray' : 'success')
                    ->action(fn (Prestador $record) => $record->update(['activo' => ! $record->activo])),
                Tables\Actions\EditAction::make()->label('Editar'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPrestadors::route('/'),
            'create' => Pages\CreatePrestador::route('/create'),
            'edit' => Pages\EditPrestador::route('/{record}/edit'),
        ];
    }
}
