<x-filament-panels::page>
    <div class="atsa-editor-layout">
        @include('filament.partials.editor-sitio-sidebar')

        <div class="atsa-editor-page-main">
            <div style="
                display: flex; align-items: flex-start; gap: 12px;
                background: #ECF2FF; border: 1px solid #d8e4ff;
                border-radius: 12px; padding: 14px 18px; margin-bottom: 20px;
            ">
                <i class="ti ti-info-circle" style="font-size:20px; color:#5d87ff; flex-shrink:0; margin-top:2px;"></i>
                <div>
                    <strong style="display:block; font-size:13px; font-weight:700; color:#2a3547; margin-bottom:2px;">¿Cómo funciona?</strong>
                    <span style="font-size:12.5px; color:#5570b3;">Editá los campos de cada sección y hacé clic en <b>Guardar cambios</b>. Los cambios se verán de inmediato en el sitio público.</span>
                </div>
            </div>

            <form wire:submit.prevent="save">
                {{ $this->form }}

                <div class="mt-6 flex items-center gap-3">
                    <x-filament::button type="submit" icon="heroicon-o-check" size="lg">
                        Guardar cambios
                    </x-filament::button>
                    <span wire:loading wire:target="save" class="text-sm text-gray-500">Guardando...</span>
                </div>
            </form>
        </div>
    </div>

    <x-filament-actions::modals />
</x-filament-panels::page>
