<x-filament-panels::page>
    {{ $this->form }}

    @if ($this->canManageBackups())
        <x-filament::section
            heading="Backups del sistema"
            description="Los respaldos manuales se guardan en storage/app/private/backups."
            class="mt-6"
        >
            <div class="space-y-3">
                @forelse ($this->getBackups() as $backup)
                    <div class="flex items-center justify-between gap-4 rounded-xl border border-gray-200 px-4 py-3 dark:border-white/10">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-gray-950 dark:text-white">{{ $backup['name'] }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                {{ $backup['created_at'] }} | {{ $backup['size'] }}
                            </p>
                        </div>

                        <x-filament::button
                            tag="a"
                            size="sm"
                            color="gray"
                            :href="route('panel.backups.download', ['filename' => $backup['name']])"
                        >
                            Descargar
                        </x-filament::button>
                    </div>
                @empty
                    <div class="rounded-xl border border-dashed border-gray-300 px-4 py-6 text-sm text-gray-600 dark:border-white/15 dark:text-gray-400">
                        Todavía no hay backups generados desde el panel.
                    </div>
                @endforelse
            </div>
        </x-filament::section>
    @endif
</x-filament-panels::page>
