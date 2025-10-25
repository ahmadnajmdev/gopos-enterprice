<?php

namespace App\Filament\Resources\SaleReturns\Pages;

use App\Filament\Resources\SaleReturns\SaleReturnResource;
use App\Models\Sale;
use App\Models\SaleReturn;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Utilities\Set;

class CreateSaleReturn extends CreateRecord
{
    protected static string $resource = SaleReturnResource::class;

    public function mount(): void
    {
        $sale_id = request()->query('sale_id');
        // Get sale data and set to form sale return
        $sale = Sale::with(['currency', 'items'])->find($sale_id);

        if ($sale) {
            $items = [];
            foreach ($sale->items as $item) {
                $items[] = [
                    'product_id' => $item->product_id,
                    'price' => $item->price,
                    'return_stock' => $item->stock,
                    'return_total_amount' => $item->total_amount,
                ];
            }

            $this->form->fill([
                'sale_id' => $sale_id,
                'currency_id' => $sale->currency_id,
                'sale_return_number' => SaleReturn::generateSaleReturnNumber(),
                'sale_return_date' => now(),
                'items' => $items,
                'sub_total' => $sale->sub_total,
                'discount' => $sale->discount,
                'total_amount' => $sale->total_amount,
                'paid_amount' => $sale->paid_amount,
                'status' => 'Pending',
            ]);
        } else {
            $this->form->fill([
                'sale_id' => null,
                'currency_id' => null,
                'sale_return_number' => SaleReturn::generateSaleReturnNumber(),
                'sale_return_date' => now(),
                'status' => 'Pending',
                'discount' => 0,
                'paid_amount' => 0,
                'sub_total' => 0,
                'total_amount' => 0,
                'items' => [],
            ]);
        }
    }
}
