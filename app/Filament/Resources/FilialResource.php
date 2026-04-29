<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\FilialResource\Pages;
use App\Models\Filial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class FilialResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = Filial::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationGroup = 'Afiliación y padrón';

    protected static ?string $navigationLabel = 'Filiales';

    protected static ?string $modelLabel = 'filial';

    protected static ?string $pluralModelLabel = 'filiales';

    protected static ?string $slug = 'filials';

    protected static ?int $navigationSort = 30;

    protected static ?string $panelScope = 'admin';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos de la filial')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug((string) $state))),

                    Forms\Components\TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(Filial::class, 'slug', ignoreRecord: true),

                    Forms\Components\TextInput::make('responsible')
                        ->label('Responsable')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('phone')
                        ->label('Teléfono')
                        ->tel()
                        ->maxLength(80),

                    Forms\Components\TextInput::make('whatsapp')
                        ->label('WhatsApp')
                        ->tel()
                        ->maxLength(80),

                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('address')
                        ->label('Dirección')
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('schedule')
                        ->label('Horario de atención')
                        ->rows(3)
                        ->columnSpanFull(),

                    Forms\Components\Toggle::make('active')
                        ->label('Activa')
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Ubicación en el mapa')
                ->columns(2)
                ->collapsed()
                ->schema([
                    Forms\Components\TextInput::make('lat')
                        ->label('Latitud')
                        ->numeric(),

                    Forms\Components\TextInput::make('lng')
                        ->label('Longitud')
                        ->numeric(),
                ]),

            Forms\Components\Section::make('Imagen')
                ->schema([
                    Forms\Components\FileUpload::make('image')
                        ->label('Foto de la filial')
                        ->image()
                        ->imageEditor()
                        ->directory('filiales')
                        ->disk('public')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('')
                    ->circular()
                    ->defaultImageUrl(fn (Filial $r): string => 'https://ui-avatars.com/api/?name='.urlencode($r->name).'&background=dbeafe&color=1d4ed8&size=80')
                    ->width(44)
                    ->height(44),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('responsible')
                    ->label('Responsable')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Teléfono')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->placeholder('—')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('active')
                    ->label('Activa')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('active')->label('Activa'),
            ])
            ->actions([
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
            'index'  => Pages\ListFilials::route('/'),
            'create' => Pages\CreateFilial::route('/create'),
            'edit'   => Pages\EditFilial::route('/{record}/edit'),
        ];
    }
}
