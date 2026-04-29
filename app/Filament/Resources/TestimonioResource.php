<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\TestimonioResource\Pages;
use App\Models\Testimonio;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TestimonioResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = Testimonio::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-oval-left-ellipsis';

    protected static ?string $navigationGroup = 'Prensa y web pública';

    protected static ?string $navigationLabel = 'Testimonios';

    protected static ?int $navigationSort = 50;

    protected static ?string $modelLabel = 'testimonio';

    protected static ?string $pluralModelLabel = 'testimonios';

    protected static ?string $slug = 'testimonios';

    protected static ?string $panelScope = 'admin';

    protected static bool $shouldRegisterNavigation = false;

    public static function getNavigationBadge(): ?string
    {
        return (string) Testimonio::where('estado', 'pendiente')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos del testimonio')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('afiliado_id')
                        ->label('Afiliado')
                        ->relationship('afiliado', 'name')
                        ->searchable()
                        ->preload()
                        ->placeholder('Sin afiliado vinculado'),

                    Forms\Components\Select::make('estado')
                        ->label('Estado')
                        ->options([
                            'pendiente'  => 'Pendiente',
                            'aprobado'   => 'Aprobado',
                            'rechazado'  => 'Rechazado',
                        ])
                        ->required()
                        ->native(false),

                    Forms\Components\TextInput::make('nombre')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('cargo')
                        ->label('Cargo / Sector')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('filial')
                        ->label('Filial')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('orden')
                        ->label('Orden')
                        ->numeric()
                        ->default(0),

                    Forms\Components\Toggle::make('activo')
                        ->label('Visible en el sitio')
                        ->helperText('Solo los testimonios activos y aprobados se muestran públicamente.')
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Contenido')
                ->schema([
                    Forms\Components\Textarea::make('texto')
                        ->label('Texto del testimonio')
                        ->required()
                        ->rows(5)
                        ->maxLength(500)
                        ->helperText('Máximo 500 caracteres.')
                        ->columnSpanFull(),

                    Forms\Components\FileUpload::make('foto')
                        ->label('Foto')
                        ->image()
                        ->imageEditor()
                        ->directory('testimonios')
                        ->disk('public')
                        ->helperText('Si no se carga foto, se usa la foto de perfil del afiliado.')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('foto')
                    ->label('')
                    ->circular()
                    ->defaultImageUrl(fn (Testimonio $r): string => 'https://ui-avatars.com/api/?name='.urlencode($r->nombre).'&background=e0f2fe&color=0369a1&size=80')
                    ->width(44)
                    ->height(44),

                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('cargo')
                    ->label('Cargo')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('filial')
                    ->label('Filial')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('texto')
                    ->label('Testimonio')
                    ->limit(60)
                    ->tooltip(fn (Testimonio $r): string => $r->texto),

                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pendiente'  => 'Pendiente',
                        'aprobado'   => 'Aprobado',
                        'rechazado'  => 'Rechazado',
                        default      => ucfirst($state),
                    })
                    ->colors([
                        'warning' => 'pendiente',
                        'success' => 'aprobado',
                        'danger'  => 'rechazado',
                    ]),

                Tables\Columns\IconColumn::make('activo')
                    ->label('Visible')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Enviado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        'pendiente'  => 'Pendiente',
                        'aprobado'   => 'Aprobado',
                        'rechazado'  => 'Rechazado',
                    ]),
                Tables\Filters\TernaryFilter::make('activo')
                    ->label('Visible en sitio'),
            ])
            ->actions([
                Tables\Actions\Action::make('aprobar')
                    ->label('Aprobar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Testimonio $r) => $r->estado !== 'aprobado')
                    ->requiresConfirmation()
                    ->modalHeading('Aprobar testimonio')
                    ->modalDescription('El testimonio se publicará en el sitio web.')
                    ->action(fn (Testimonio $r) => $r->update(['estado' => 'aprobado', 'activo' => true])),

                Tables\Actions\Action::make('rechazar')
                    ->label('Rechazar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Testimonio $r) => $r->estado !== 'rechazado')
                    ->requiresConfirmation()
                    ->modalHeading('Rechazar testimonio')
                    ->modalDescription('El testimonio no se publicará.')
                    ->action(fn (Testimonio $r) => $r->update(['estado' => 'rechazado', 'activo' => false])),

                Tables\Actions\Action::make('desactivar')
                    ->label('Desactivar')
                    ->icon('heroicon-o-eye-slash')
                    ->color('gray')
                    ->visible(fn (Testimonio $r) => $r->activo)
                    ->action(fn (Testimonio $r) => $r->update(['activo' => false])),

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
            'index'  => Pages\ListTestimonios::route('/'),
            'create' => Pages\CreateTestimonio::route('/create'),
            'edit'   => Pages\EditTestimonio::route('/{record}/edit'),
        ];
    }
}
