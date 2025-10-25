<?php

namespace App\Livewire;

use App\Models\Purchase;
use Livewire\Component;

class InvoicePDF extends Component
{
    public $purchase;

    public function mount(Purchase $purchase)
    {
        $this->purchase = $purchase;
    }

    public function render()
    {
        return view('livewire.invoice-p-d-f');
    }
}
