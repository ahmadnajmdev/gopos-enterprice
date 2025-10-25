<x-filament-panels::page>

    <div id="invoice" wire:ignore>
        @livewire('invoice-p-d-f', ['purchase' => $purchase])
    </div>
</x-filament-panels::page>
