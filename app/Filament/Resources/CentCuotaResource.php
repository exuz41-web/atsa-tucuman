<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\CentCuotaResource\Pages;
use App\Models\CentCuota;
use App\Models\MatriculaCent;
use App\Models\User;
use App\Services\Cent\CentNotificar;
use App\Services\Cent\EmitirReciboCuota;
use App\Services\Cent\FilamentSedeScope;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CentCuotaResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = CentCuota::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Administración';
    protected static ?string $navigationLabel = 'Cuotas';
    protected static ?string $modelLabel = 'cuota';
    protected static ?string $pluralModelLabel = 'cuotas y pagos';
    protected static ?string $slug = 'cuotas';
    protected static ?string $panelScope = 'cent';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('alumno_id')
                ->label('Alumno')
                ->options(fn () => User::where('cent_role', 'alumno')->orderBy('name')->pluck('name', 'id'))
                ->searchable()
                ->required(),
            Forms\Components\Select::make('matricula_cent_id')
                ->label('Matrícula')
                ->options(fn () => MatriculaCent::with(['alumno', 'carrera'])->latest()->get()->mapWithKeys(fn ($m) => [$m->id => (($m->alumno->name ?? 'Alumno')).' - '.(($m->carrera->name ?? 'Carrera')).' - '.$m->ciclo_lectivo]))
                ->searchable(),
            Forms\Components\TextInput::make('concepto')->required()->maxLength(255),
            Forms\Components\TextInput::make('periodo')->placeholder('Ej: Abril 2026'),
            Forms\Components\TextInput::make('monto')->numeric()->required()->default(0),
            Forms\Components\Select::make('descuento_tipo')->label('Tipo de descuento')->options(self::descuentos())->default('ninguno')->required()->native(false),
            Forms\Components\TextInput::make('descuento_porcentaje')->label('% descuento')->numeric()->default(0),
            Forms\Components\TextInput::make('descuento_monto')->label('$ descuento')->numeric()->default(0),
            Forms\Components\Select::make('afiliado_descuento_id')
                ->label('Afiliado que habilita el descuento')
                ->helperText('Usar cuando el alumno es afiliado ATSA o hijo/familiar de afiliado.')
                ->options(fn () => User::whereNotNull('numero_afiliado')->orderBy('name')->pluck('name', 'id'))
                ->searchable(),
            Forms\Components\DatePicker::make('vencimiento')->native(false),
            Forms\Components\Select::make('estado')->options(self::estados())->default('pendiente')->required()->native(false),
            Forms\Components\FileUpload::make('comprobante')->disk('local')->directory('cent/cuotas')->helperText('Archivo privado. La descarga se realiza desde acciones.'),
            Forms\Components\DateTimePicker::make('pagado_at')->label('Pagado el')->native(false),
            Forms\Components\Textarea::make('observaciones')->rows(3)->columnSpanFull(),
            Forms\Components\Hidden::make('creado_por')->default(fn () => auth()->id()),
        ])->columns(2);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return FilamentSedeScope::sedeId()
            ? parent::getEloquentQuery()->whereHas('matricula', fn ($q) => $q->where('cent_sede_id', FilamentSedeScope::sedeId()))
            : parent::getEloquentQuery();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('alumno.name')->label('Alumno')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('concepto')->searchable(),
                Tables\Columns\TextColumn::make('periodo')->searchable(),
                Tables\Columns\TextColumn::make('monto')->money('ARS')->sortable(),
                Tables\Columns\TextColumn::make('monto_final')->label('Final')->money('ARS')->sortable(),
                Tables\Columns\TextColumn::make('descuento_tipo')->label('Descuento')->badge()->formatStateUsing(fn ($state) => self::descuentos()[$state] ?? $state),
                Tables\Columns\TextColumn::make('afiliadoDescuento.numero_afiliado')->label('Afiliado desc.')->placeholder('-'),
                Tables\Columns\TextColumn::make('vencimiento')->date('d/m/Y')->sortable(),
                Tables\Columns\TextColumn::make('estado')->badge()->color(fn ($state) => match ($state) {
                    'pagada', 'bonificada' => 'success',
                    'vencida' => 'danger',
                    'anulada' => 'gray',
                    default => 'warning',
                }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')->options(self::estados()),
                Tables\Filters\SelectFilter::make('descuento_tipo')->options(self::descuentos()),
            ])
            ->actions([
                Tables\Actions\Action::make('comprobante')
                    ->label('Comprobante')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->visible(fn (CentCuota $record) => filled($record->comprobante))
                    ->url(fn (CentCuota $record) => route('cent.archivos.cuotas.comprobante', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('marcarPagada')
                    ->label('Pagar y emitir recibo')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->estado !== 'pagada')
                    ->action(function (CentCuota $record): void {
                        $record->update(['estado' => 'pagada', 'pagado_at' => now()]);
                        $recibo = app(EmitirReciboCuota::class)->ejecutar($record, auth()->id());
                        CentNotificar::usuario($record->alumno_id, 'Pago confirmado', 'Se confirmó el pago de '.$record->concepto.'. Ya podés descargar el recibo.', 'cuota', route('cent.alumno.recibos.pdf', $recibo));
                    }),
                Tables\Actions\Action::make('recibo')
                    ->label('Recibo')
                    ->icon('heroicon-o-printer')
                    ->visible(fn ($record) => (bool) $record->recibo)
                    ->url(fn ($record) => route('cent.alumno.recibos.pdf', $record->recibo))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function descuentos(): array
    {
        return [
            'ninguno' => 'Sin descuento',
            'afiliado_atsa' => 'Afiliado ATSA',
            'hijo_afiliado_atsa' => 'Hijo/familiar de afiliado ATSA',
            'beca' => 'Beca',
            'otro' => 'Otro',
        ];
    }

    public static function estados(): array
    {
        return [
            'pendiente' => 'Pendiente',
            'pagada' => 'Pagada',
            'vencida' => 'Vencida',
            'bonificada' => 'Bonificada',
            'anulada' => 'Anulada',
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
            'index' => Pages\ListCentCuotas::route('/'),
            'create' => Pages\CreateCentCuota::route('/create'),
            'edit' => Pages\EditCentCuota::route('/{record}/edit'),
        ];
    }
}
