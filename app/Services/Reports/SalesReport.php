<?php

namespace App\Services\Reports;

use App\Models\Sale;
use Illuminate\Support\Collection;

class SalesReport extends BaseReport
{
    protected string $title = 'Sales Report';

    protected bool $showTotals = true;

    protected array $columns = [
        'sale_date' => ['label' => 'Date', 'type' => 'date'],
        'sale_number' => ['label' => 'Sale number', 'type' => 'text'],
        'customer_name' => ['label' => 'Customer', 'type' => 'text'],
        'sub_total' => ['label' => 'Sub Total', 'type' => 'currency'],
        'discount_amount' => ['label' => 'Discount', 'type' => 'currency'],
        'total_amount' => ['label' => 'Total', 'type' => 'currency'],
        'paid_amount' => ['label' => 'Paid amount', 'type' => 'currency'],
    ];

    protected array $totalColumns = ['discount_amount', 'total_amount', 'paid_amount'];

    public function getData(string $startDate, string $endDate): Collection
    {
        return Sale::query()
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->with(['customer', 'currency'])
            ->get()
            ->map(function ($sale) {
                return [
                    'sale_date' => $sale->sale_date,
                    'sale_number' => $sale->sale_number,
                    'customer_name' => $sale->customer?->name,
                    'sub_total' => $sale->sub_total,
                    'discount_amount' => $sale->discount_amount,
                    'total_amount' => $sale->total_amount,
                    'paid_amount' => $sale->paid_amount,
                    'currency' => $sale->currency?->symbol ?? $sale->currency?->code ?? __('IQD'),
                ];
            });
    }
}
