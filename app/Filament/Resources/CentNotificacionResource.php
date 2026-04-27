<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CentNotificacionResource\Pages;
use App\Models\CentNotificacion;
use App\Models\CentSede;
use App\Models\User;
use App\Services\Cent\FilamentSedeScope;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CentNotificacionResource extends Resource
{
    protected static ?string $model = CentNotificacion::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';
    protected static ?string $navigationGroup = 'Administración';
    protected static ?string $navigationLabel = 'Notificaciones';
    protected static ?string $modelLabel = 'notificación';
    protected static ?string $pluralModelLabel = 'notificaciones';
    protected static ?string $slug = 'notificaciones';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Destino')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Usuario')
                            ->options(fn () => User::whereNotNull('cent_role')->orderBy('name')->pluck('name', 'id'))
                            ->searchable(),
                        Forms\Components\Select::make('cent_sede_id')
                            ->label('Sede')
                            ->options(fn () => CentSede::orderBy('nombre')->pluck('nombre', 'id'))
                            ->searchable()
                            ->helperText('Opcional. Usar para avisos dirigidos a una sede completa.'),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Mensaje')
                    ->schema([
                        Forms\Components\TextInput::make('titulo')->required()->maxLength(255),
                        Forms\Components\Select::make('tipo')
                            ->options([
                                'info' => 'Información',
                                'cuota' => 'Cuota',
                                'legajo' => 'Legajo',
                                'aula' => 'Aula virtual',
                                'permiso' => 'Permiso de examen',
                                'mesa' => 'Mesa de examen',
                                'sistema' => 'Sistema',
                            ])
                            ->default('info')
                            ->required()
                            ->native(false),
                        Forms\Components\TextInput::make('url')
                            ->label('Enlace')
                            ->maxLength(255)
                            ->helperText('Puede ser una URL interna del portal.'),
                        Forms\Components\Textarea::make('mensaje')->required()->rows(5)->columnSpanFull(),
                        Forms\Components\DateTimePicker::make('leida_at')->label('Leída el')->native(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('titulo')->searchable()->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('user.name')->label('Usuario')->searchable()->placeholder('General'),
                Tables\Columns\TextColumn::make('sede.nombre')->label('Sede')->placeholder('-'),
                Tables\Columns\TextColumn::make('tipo')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'cuota' => 'warning',
                        'legajo', 'aula' => 'info',
                        'permiso', 'mesa' => 'success',
                        'sistema' => 'gray',
                        default => 'primary',
                    }),
                Tables\Columns\IconColumn::make('leida_at')
                    ->label('Leída')
                    ->boolean()
                    ->getStateUsing(fn (CentNotificacion $record) => filled($record->leida_at)),
                Tables\Columns\TextColumn::make('created_at')->label('Fecha')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo')
                    ->options([
                        'info' => 'Información',
                        'cuota' => 'Cuota',
                        'legajo' => 'Legajo',
                        'aula' => 'Aula virtual',
                        'permiso' => 'Permiso',
                        'mesa' => 'Mesa',
                        'sistema' => 'Sistema',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return FilamentSedeScope::sedeId()
            ? parent::getEloquentQuery()->where('cent_sede_id', FilamentSedeScope::sedeId())
            : parent::getEloquentQuery();
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Filament::getCurrentPanel()?->getId() === 'cent';
    }

    public static function canAccess(): bool
    {
        return Filament::getCurrentPanel()?->getId() === 'cent';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCentNotificacions::route('/'),
            'create' => Pages\CreateCentNotificacion::route('/create'),
            'edit' => Pages\EditCentNotificacion::route('/{record}/edit'),
        ];
    }
}
