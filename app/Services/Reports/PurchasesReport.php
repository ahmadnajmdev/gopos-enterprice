<?php

namespace App\Services\Reports;

use App\Models\Purchase;
use Illuminate\Support\Collection;

class PurchasesReport extends BaseReport
{
    protected string $title = 'Purchases Report';

    protected bool $showTotals = true;

    protected array $columns = [
        'purchase_date' => ['label' => 'Date', 'type' => 'date'],
        'purchase_number' => ['label' => 'Purchase Number', 'type' => 'text'],
        'supplier_name' => ['label' => 'Supplier', 'type' => 'text'],
        'sub_total' => ['label' => 'Sub Total', 'type' => 'currency'],
        'discount_amount' => ['label' => 'Discount', 'type' => 'currency'],
        'total_amount' => ['label' => 'Total', 'type' => 'currency'],
        'paid_amount' => ['label' => 'Paid amount', 'type' => 'currency'],
    ];

    protected array $totalColumns = ['discount_amount', 'total_amount', 'paid_amount'];

    public function getData(string $startDate, string $endDate): Collection
    {
        return Purchase::query()
            ->whereBetween('purchase_date', [$startDate, $endDate])
            ->with(['supplier', 'currency'])
            ->get()
            ->map(function ($purchase) {
                return [
                    'purchase_date' => $purchase->purchase_date,
                    'purchase_number' => $purchase->purchase_number,
                    'supplier_name' => $purchase->supplier->name,
                    'sub_total' => $purchase->sub_total,
                    'discount_amount' => $purchase->discount_amount,
                    'total_amount' => $purchase->total_amount,
                    'paid_amount' => $purchase->paid_amount,
                    'currency' => $purchase->currency?->symbol ?? $purchase->currency?->code ?? __('IQD'),
                ];
            });
    }
}
