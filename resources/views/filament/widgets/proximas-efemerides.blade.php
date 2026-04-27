<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Proximas efemerides</x-slot>
        <x-slot name="description">Fechas importantes del sector salud</x-slot>

        <div class="space-y-4">
            @forelse ($efemerides as $efemeride)
                <div class="flex items-start gap-4 rounded-xl bg-gray-50 p-4">
                    <div class="flex h-14 w-14 shrink-0 flex-col items-center justify-center rounded-xl bg-primary-100 text-primary-700">
                        <span class="text-xl font-bold">{{ $efemeride->dia }}</span>
                        <span class="text-[10px] uppercase">{{ substr($meses[$efemeride->mes] ?? '', 0, 3) }}</span>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-950">{{ $efemeride->titulo }}</p>
                        <p class="text-sm text-gray-500">{{ $efemeride->descripcion }}</p>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">No hay efemerides cargadas.</p>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
