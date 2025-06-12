<x-filament-widgets::widget>
    <x-filament::section>
        <form wire:submit.prevent="applyFilters">
            {{ $this->form }}

            <div class="flex justify-center gap-4 pt-4 mt-3">
                <x-filament::button
                    type="button"
                    wire:click="resetFilters"
                    color="gray"
                    size="lg"
                    outlined>
                    {{ __('filament.reset') }}
                </x-filament::button>

                <x-filament::button
                    type="submit"
                    color="primary"
                    size="lg">
                    {{ __('filament.apply') }}
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>
</x-filament-widgets::widget>
