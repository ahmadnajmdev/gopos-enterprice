<?php

namespace App\Livewire;

use App\Models\Sale;
use Livewire\Component;

class SaleInvoice extends Component
{
    public $sale;

    public function mount(Sale $sale)
    {
        $this->sale = $sale;
    }

    public function render()
    {
        return view('livewire.sale-invoice');
    }
}
