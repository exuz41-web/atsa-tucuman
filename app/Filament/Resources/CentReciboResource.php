<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\CentReciboResource\Pages;
use App\Models\CentRecibo;
use App\Models\CentCuota;
use App\Models\User;
use App\Services\Cent\FilamentSedeScope;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CentReciboResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = CentRecibo::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-refund';
    protected static ?string $navigationGroup = 'Administración';
    protected static ?string $navigationLabel = 'Recibos';
    protected static ?string $modelLabel = 'recibo';
    protected static ?string $pluralModelLabel = 'recibos oficiales';
    protected static ?string $slug = 'recibos';
    protected static ?string $panelScope = 'cent';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Datos del recibo')
                    ->schema([
                        Forms\Components\TextInput::make('numero')->label('Número')->maxLength(255),
                        Forms\Components\Select::make('alumno_id')
                            ->label('Alumno')
                            ->options(fn () => User::where('cent_role', 'alumno')->orderBy('name')->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('cent_cuota_id')
                            ->label('Cuota')
                            ->options(fn () => CentCuota::with('alumno')->latest()->limit(300)->get()->mapWithKeys(fn ($cuota) => [
                                $cuota->id => (($cuota->alumno->name ?? 'Alumno')).' - '.$cuota->concepto.' - '.$cuota->periodo,
                            ]))
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('concepto')->required()->maxLength(255),
                        Forms\Components\TextInput::make('periodo')->maxLength(255),
                        Forms\Components\TextInput::make('monto')->numeric()->required()->prefix('$'),
                        Forms\Components\TextInput::make('qr_token')->label('Token QR')->maxLength(255),
                        Forms\Components\DateTimePicker::make('emitido_at')->label('Emitido el')->native(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('numero')->label('Número')->searchable()->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('alumno.name')->label('Alumno')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('concepto')->searchable(),
                Tables\Columns\TextColumn::make('periodo')->placeholder('-'),
                Tables\Columns\TextColumn::make('monto')->money('ARS')->sortable(),
                Tables\Columns\TextColumn::make('emitido_at')->label('Emitido')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('emitidos_hoy')
                    ->label('Emitidos hoy')
                    ->query(fn ($query) => $query->whereDate('emitido_at', now()->toDateString())),
            ])
            ->actions([
                Tables\Actions\Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-printer')
                    ->url(fn (CentRecibo $record) => route('cent.alumno.recibos.pdf', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('verificar')
                    ->label('Verificar')
                    ->icon('heroicon-o-qr-code')
                    ->url(fn (CentRecibo $record) => route('cent.recibos.verificar', $record->qr_token))
                    ->openUrlInNewTab(),
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
            ? parent::getEloquentQuery()->whereHas('cuota.matricula', fn ($query) => $query->where('cent_sede_id', FilamentSedeScope::sedeId()))
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
        return Filament::getCurrentPanel()?->getId() === 'cent' && static::canViewAny();
    }

    public static function canAccess(): bool
    {
        return Filament::getCurrentPanel()?->getId() === 'cent' && static::canViewAny();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCentRecibos::route('/'),
            'create' => Pages\CreateCentRecibo::route('/create'),
            'edit' => Pages\EditCentRecibo::route('/{record}/edit'),
        ];
    }
}
