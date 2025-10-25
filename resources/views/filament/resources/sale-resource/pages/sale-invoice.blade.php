<x-filament-panels::page>

    <div id="invoice" wire:ignore>
        @livewire('sale-invoice', ['sale' => $sale])
    </div>

    <script>
        function printInvoice() {
            var printContents = document.getElementById('invoice').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
</x-filament-panels::page>
