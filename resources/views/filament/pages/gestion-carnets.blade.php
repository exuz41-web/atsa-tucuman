<x-filament-panels::page>

    @php
        $porVencer = \App\Models\User::where('carnet_activo', true)
            ->whereNotNull('carnet_vencimiento')
            ->whereBetween('carnet_vencimiento', [now()->startOfDay(), now()->addDays(30)->endOfDay()])
            ->count();
        $vencidos = \App\Models\User::where('carnet_activo', true)
            ->whereNotNull('carnet_vencimiento')
            ->where('carnet_vencimiento', '<', now()->startOfDay())
            ->count();
        $sinCarnet = \App\Models\User::where('role', 'afiliado')
            ->whereNotNull('numero_afiliado')
            ->where(fn($q) => $q->whereNull('carnet_activo')->orWhere('carnet_activo', false))
            ->count();
    @endphp

    @if($vencidos > 0 || $porVencer > 0 || $sinCarnet > 0)
    <div class="mb-6 grid gap-3 sm:grid-cols-3">
        @if($vencidos > 0)
        <div class="flex items-center gap-3 rounded-xl border border-danger-200 bg-danger-50 px-4 py-3">
            <x-heroicon-o-exclamation-circle class="h-6 w-6 shrink-0 text-danger-500"/>
            <div>
                <p class="text-sm font-bold text-danger-700">{{ $vencidos }} carnet{{ $vencidos > 1 ? 's' : '' }} vencido{{ $vencidos > 1 ? 's' : '' }}</p>
                <p class="text-xs text-danger-500">Requieren renovación inmediata</p>
            </div>
        </div>
        @endif

        @if($porVencer > 0)
        <div class="flex items-center gap-3 rounded-xl border border-warning-200 bg-warning-50 px-4 py-3">
            <x-heroicon-o-clock class="h-6 w-6 shrink-0 text-warning-500"/>
            <div>
                <p class="text-sm font-bold text-warning-700">{{ $porVencer }} por vencer en 30 días</p>
                <p class="text-xs text-warning-500">Renovar antes del vencimiento</p>
            </div>
        </div>
        @endif

        @if($sinCarnet > 0)
        <div class="flex items-center gap-3 rounded-xl border border-gray-200 bg-gray-50 px-4 py-3">
            <x-heroicon-o-identification class="h-6 w-6 shrink-0 text-gray-400"/>
            <div>
                <p class="text-sm font-bold text-gray-700">{{ $sinCarnet }} sin carnet activo</p>
                <p class="text-xs text-gray-500">Afiliados sin credencial emitida</p>
            </div>
        </div>
        @endif
    </div>
    @endif

    {{ $this->table }}

</x-filament-panels::page>
