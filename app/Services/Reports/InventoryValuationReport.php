<?php

namespace App\Services\Reports;

use App\Models\Product;
use Illuminate\Support\Collection;

class InventoryValuationReport extends BaseReport
{
    protected string $title = 'Inventory Valuation Report';

    protected bool $showTotals = true;

    protected array $columns = [
        'name' => ['label' => 'Product Name', 'type' => 'text'],
        'barcode' => ['label' => 'Barcode', 'type' => 'text'],
        'category' => ['label' => 'Category', 'type' => 'text'],
        'quantity' => ['label' => 'Stock Quantity', 'type' => 'text'],
        'cost_price' => ['label' => 'Unit Cost', 'type' => 'currency'],
        'total_value' => ['label' => 'Total Value', 'type' => 'currency'],
    ];

    protected array $totalColumns = ['total_value'];

    public function getData(string $startDate, string $endDate): Collection
    {
        $baseCurrency = \App\Models\Currency::getBaseCurrency();
        $decimalPlaces = $baseCurrency?->decimal_places ?? 2;

        return Product::query()
            ->with(['category', 'movements', 'unit'])
            ->get()
            ->map(function ($product) use ($baseCurrency, $decimalPlaces) {
                $stock = $product->movements()->sum('quantity');

                if ($stock <= 0) {
                    return null;
                }

                $totalValue = $stock * $product->cost;
                $unitAbbr = $product->unit?->abbreviation ?? '';

                return [
                    'name' => $product->name,
                    'barcode' => $product->barcode ?? 'N/A',
                    'category' => $product->category?->name ?? 'Uncategorized',
                    'quantity' => number_format($stock, 2) . ($unitAbbr ? ' ' . $unitAbbr : ''),
                    'cost_price' => round($product->cost, $decimalPlaces),
                    'total_value' => round($totalValue, $decimalPlaces),
                    'currency' => $baseCurrency?->symbol,
                ];
            })
            ->filter();
    }
}
