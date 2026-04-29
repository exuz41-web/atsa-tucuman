<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\HotelConvenioResource\Pages;
use App\Models\HotelConvenio;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HotelConvenioResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = HotelConvenio::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'Secretarías y beneficios';

    protected static ?string $navigationLabel = 'Hoteles convenio';

    protected static ?string $modelLabel = 'hotel convenio';

    protected static ?string $pluralModelLabel = 'hoteles convenio';

    protected static ?string $slug = 'hotel-convenios';

    protected static ?int $navigationSort = 60;

    protected static ?string $panelScope = 'admin';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos del establecimiento')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('nombre')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Forms\Components\Select::make('tipo')
                        ->label('Tipo')
                        ->options(self::tipos())
                        ->required()
                        ->native(false),

                    Forms\Components\TextInput::make('orden')
                        ->label('Orden')
                        ->numeric()
                        ->default(0),

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

                    Forms\Components\Toggle::make('activo')
                        ->label('Activo / Visible')
                        ->helperText('Solo los hoteles activos se muestran en el portal.')
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Descripción')
                ->schema([
                    Forms\Components\Textarea::make('descripcion')
                        ->label('Descripción')
                        ->rows(5)
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Imagen y enlaces')
                ->columns(2)
                ->schema([
                    Forms\Components\FileUpload::make('imagen')
                        ->label('Imagen')
                        ->image()
                        ->imageEditor()
                        ->directory('hoteles-convenio')
                        ->disk('public')
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('mapa_url')
                        ->label('Enlace a mapa (Google Maps)')
                        ->url()
                        ->placeholder('https://maps.google.com/...')
                        ->maxLength(500),

                    Forms\Components\TextInput::make('web_url')
                        ->label('Sitio web')
                        ->url()
                        ->placeholder('https://')
                        ->maxLength(500),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('orden')
            ->reorderable('orden')
            ->columns([
                Tables\Columns\ImageColumn::make('imagen')
                    ->label('')
                    ->width(64)
                    ->height(44)
                    ->defaultImageUrl(asset('images/placeholder.png')),

                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('tipo')
                    ->label('Tipo')
                    ->formatStateUsing(fn (?string $state): string => self::tipos()[$state] ?? ucfirst((string) $state))
                    ->color('info'),

                Tables\Columns\TextColumn::make('localidad')
                    ->label('Localidad')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('provincia')
                    ->label('Provincia')
                    ->placeholder('—'),

                Tables\Columns\IconColumn::make('mapa_url')
                    ->label('Mapa')
                    ->boolean()
                    ->trueIcon('heroicon-o-map-pin')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\IconColumn::make('activo')
                    ->label('Activo')
                    ->boolean(),

                Tables\Columns\TextColumn::make('orden')
                    ->label('Orden')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo')
                    ->label('Tipo')
                    ->options(self::tipos()),
                Tables\Filters\TernaryFilter::make('activo')
                    ->label('Activo'),
            ])
            ->actions([
                Tables\Actions\Action::make('toggle_activo')
                    ->label(fn (HotelConvenio $r) => $r->activo ? 'Desactivar' : 'Activar')
                    ->icon(fn (HotelConvenio $r) => $r->activo ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                    ->color(fn (HotelConvenio $r) => $r->activo ? 'gray' : 'success')
                    ->action(fn (HotelConvenio $r) => $r->update(['activo' => ! $r->activo])),

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
            'index'  => Pages\ListHotelConvenios::route('/'),
            'create' => Pages\CreateHotelConvenio::route('/create'),
            'edit'   => Pages\EditHotelConvenio::route('/{record}/edit'),
        ];
    }

    public static function tipos(): array
    {
        return [
            'hotel'               => 'Hotel',
            'hosteria'            => 'Hostería',
            'apart_hotel'         => 'Apart hotel',
            'complejo'            => 'Complejo',
            'complejo_recreativo' => 'Complejo recreativo',
            'camping'             => 'Camping',
            'otro'                => 'Otro',
        ];
    }
}
