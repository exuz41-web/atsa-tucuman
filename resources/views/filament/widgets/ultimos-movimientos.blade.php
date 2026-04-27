<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Últimos movimientos</x-slot>
        <x-slot name="description">Pedidos, consultas, afiliaciones, turismo y noticias recientes</x-slot>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 text-left text-xs font-semibold uppercase text-gray-500">
                        <th class="px-3 py-3 w-28">Tipo</th>
                        <th class="px-3 py-3">Descripción</th>
                        <th class="px-3 py-3 hidden md:table-cell">Usuario / Contacto</th>
                        <th class="px-3 py-3 hidden sm:table-cell">Fecha</th>
                        <th class="px-3 py-3">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($movimientos as $movimiento)
                        @php
                            $tipoColor = match($movimiento['tipo']) {
                                'Afiliación' => 'text-amber-700 bg-amber-50',
                                'Pedido'     => 'text-blue-700 bg-blue-50',
                                'Consulta'   => 'text-red-700 bg-red-50',
                                'Turismo'    => 'text-teal-700 bg-teal-50',
                                'Noticia'    => 'text-green-700 bg-green-50',
                                default      => 'text-gray-700 bg-gray-50',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-3 py-2.5">
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-bold {{ $tipoColor }}">
                                    {{ $movimiento['tipo'] }}
                                </span>
                            </td>
                            <td class="px-3 py-2.5 font-medium text-gray-800 max-w-xs truncate">
                                {{ $movimiento['descripcion'] }}
                            </td>
                            <td class="px-3 py-2.5 text-gray-500 hidden md:table-cell">
                                {{ $movimiento['usuario'] }}
                            </td>
                            <td class="px-3 py-2.5 text-gray-400 text-xs hidden sm:table-cell whitespace-nowrap">
                                {{ optional($movimiento['fecha'])->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-3 py-2.5">
                                <x-filament::badge :color="$movimiento['color']">
                                    {{ str_replace('_', ' ', $movimiento['estado']) }}
                                </x-filament::badge>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-3 py-8 text-center text-gray-400 text-sm">
                                Todavía no hay movimientos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
