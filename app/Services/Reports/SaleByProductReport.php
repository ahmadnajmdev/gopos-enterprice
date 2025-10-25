<?php

namespace App\Services\Reports;

use App\Models\Product;
use Illuminate\Support\Collection;

class SaleByProductReport extends BaseReport
{
    protected string $title = 'Sales By Product Report';

    protected bool $showTotals = true;

    protected array $columns = [
        // Define your columns here
        // 'column_key' => ['label' => 'Column Label', 'type' => 'text|currency|number|date'],
        'name' => ['label' => 'Product Name', 'type' => 'text'],
        'quantity_sold' => ['label' => 'Quantity Sold', 'type' => 'number'],
        'unit' => ['label' => 'Unit', 'type' => 'text'],
        'price' => ['label' => 'Price', 'type' => 'currency'],
        'total_amount' => ['label' => 'Total Amount', 'type' => 'currency'],
    ];

    protected array $totalColumns = [
        'quantity_sold',
        'total_amount',
    ];

    public function getData(string $startDate, string $endDate): Collection|array
    {
        $products = Product::query()->with(['saleItems' => function ($query) use ($startDate, $endDate) {
            $query->whereHas('sale', function ($saleQuery) use ($startDate, $endDate) {
                $saleQuery->whereBetween('sale_date', [$startDate, $endDate]);
            });
        }])->get();

        return $products->map(function ($product) {
            $quantitySoldValue = (float) $product->saleItems->sum('quantity');
            $unit = $product->unit?->abbreviation ?? '';
            $totalAmount = (float) $product->saleItems->sum('total_amount');

            return [
                'name' => $product->name,
                'quantity_sold' => $quantitySoldValue,
                'unit' => $unit,
                'price' => $product->price,
                'total_amount' => $totalAmount,
            ];
        });
    }
}
