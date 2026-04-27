<?php

namespace App\Models;

use App\Helpers\LogActividad;
use Filament\Notifications\Actions\Action as NotificationAction;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class OrdenPrestacion extends Model
{
    protected $table = 'ordenes_prestacion';

    protected $fillable = [
        'codigo',
        'prestador_id',
        'afiliado_id',
        'pedido_id',
        'solicitud_beneficio_id',
        'tipo',
        'estado',
        'detalle',
        'observaciones_internas',
        'respuesta_prestador',
        'emitida_at',
        'aceptada_at',
        'entregada_at',
        'emitida_por',
        'cerrada_por',
    ];

    protected function casts(): array
    {
        return [
            'emitida_at' => 'datetime',
            'aceptada_at' => 'datetime',
            'entregada_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $orden): void {
            $orden->codigo ??= self::generarCodigo();
            $orden->estado ??= 'emitida';
            $orden->emitida_at ??= now();
            $orden->emitida_por ??= auth()->id();
        });
    }

    public function prestador(): BelongsTo
    {
        return $this->belongsTo(Prestador::class);
    }

    public function afiliado(): BelongsTo
    {
        return $this->belongsTo(User::class, 'afiliado_id');
    }

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function solicitudBeneficio(): BelongsTo
    {
        return $this->belongsTo(SolicitudBeneficio::class);
    }

    public function emitidaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'emitida_por');
    }

    public function cerradaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cerrada_por');
    }

    public function registrarEntrega(?string $respuestaPrestador = null, ?int $cerradaPor = null): void
    {
        if ($this->estado === 'entregada') {
            return;
        }

        $this->loadMissing(['prestador', 'afiliado', 'pedido', 'solicitudBeneficio.beneficio']);

        $mensajeAfiliado = 'El prestador '.$this->prestador?->nombre.' registró la entrega de la orden '.$this->codigo.'.';
        $respuesta = $respuestaPrestador ?: $this->respuesta_prestador;

        $this->update([
            'estado' => 'entregada',
            'entregada_at' => now(),
            'cerrada_por' => $cerradaPor,
            'respuesta_prestador' => $respuesta,
        ]);

        if ($this->pedido) {
            $this->pedido->update([
                'estado' => 'entregado',
                'entregado_at' => now(),
                'observacion_afiliado' => $mensajeAfiliado,
            ]);
        }

        if ($this->solicitudBeneficio) {
            $this->solicitudBeneficio->update([
                'estado' => 'entregada',
                'entregado_at' => now(),
                'observacion_afiliado' => $mensajeAfiliado,
            ]);
        }

        $this->notificarEntrega($mensajeAfiliado);

        LogActividad::registrar(
            'registro entrega prestador',
            'OrdenPrestacion',
            $this->id,
            $this->codigo.' - '.$this->prestador?->nombre
        );
    }

    public static function estados(): array
    {
        return [
            'emitida' => 'Emitida',
            'aceptada' => 'Aceptada por prestador',
            'observada' => 'Observada',
            'entregada' => 'Entregada',
            'anulada' => 'Anulada',
        ];
    }

    public static function estadoColor(?string $estado): string
    {
        return match ($estado) {
            'emitida' => 'warning',
            'aceptada' => 'info',
            'observada' => 'warning',
            'entregada' => 'success',
            'anulada' => 'danger',
            default => 'gray',
        };
    }

    public static function tipos(): array
    {
        return [
            'anteojos' => 'Anteojos',
            'medicacion' => 'Medicación',
            'turismo' => 'Turismo',
            'salud' => 'Salud',
            'convenio' => 'Convenio',
            'otro' => 'Otro',
        ];
    }

    public static function generarCodigo(): string
    {
        $nextId = ((int) static::max('id')) + 1;

        return 'ORD-'.now()->format('Y').'-'.str_pad((string) $nextId, 6, '0', STR_PAD_LEFT);
    }

    private function notificarEntrega(string $mensajeAfiliado): void
    {
        if ($this->afiliado) {
            Notification::make()
                ->title('Orden entregada')
                ->body($mensajeAfiliado)
                ->color('success')
                ->sendToDatabase($this->afiliado);
        }

        $destinatarios = $this->destinatariosInternos();

        if ($destinatarios->isEmpty()) {
            return;
        }

        Notification::make()
            ->title('Entrega registrada por prestador')
            ->body(($this->prestador?->nombre ?: 'Un prestador').' registró la entrega de '.$this->codigo.' para '.$this->afiliado?->name.'.')
            ->icon('heroicon-o-check-badge')
            ->success()
            ->actions([
                NotificationAction::make('ver')
                    ->label('Ver orden')
                    ->url(url('/admin/ordenes-prestacion/'.$this->id.'/edit')),
            ])
            ->sendToDatabase($destinatarios, true);
    }

    private function destinatariosInternos(): Collection
    {
        $secretariaId = $this->pedido?->secretaria_id ?: $this->solicitudBeneficio?->secretaria_id;

        return User::query()
            ->where('active', true)
            ->get()
            ->filter(function (User $user) use ($secretariaId): bool {
                if ($user->hasPermission('admin.atencion.manage') || $user->hasPermission('admin.*') || $user->hasPermission('*')) {
                    return true;
                }

                return filled($secretariaId) && (int) $user->secretaria_id === (int) $secretariaId;
            })
            ->values();
    }
}
