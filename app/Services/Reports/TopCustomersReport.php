<?php

namespace App\Services\Reports;

use App\Models\Customer;
use Illuminate\Support\Collection;

class TopCustomersReport extends BaseReport
{
    protected string $title = 'Top Customers Report';

    protected bool $showTotals = true;

    protected array $columns = [
        'rank' => ['label' => 'Rank', 'type' => 'text'],
        'name' => ['label' => 'Customer Name', 'type' => 'text'],
        'phone' => ['label' => 'Phone', 'type' => 'text'],
        'total_orders' => ['label' => 'Total Orders', 'type' => 'number'],
        'total_amount' => ['label' => 'Total Amount', 'type' => 'currency'],
    ];

    protected array $totalColumns = ['total_orders', 'total_amount'];

    public function getData(string $startDate, string $endDate): Collection
    {
        $baseCurrency = \App\Models\Currency::getBaseCurrency();
        $decimalPlaces = $baseCurrency?->decimal_places ?? 2;

        return Customer::query()
            ->with(['sales' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('sale_date', [$startDate, $endDate]);
            }])
            ->get()
            ->map(function ($customer) use ($baseCurrency, $decimalPlaces) {
                $totalOrders = $customer->sales->count();
                $totalAmount = $customer->sales->sum('amount_in_base_currency');

                return [
                    'name' => $customer->name,
                    'phone' => $customer->phone ?? 'N/A',
                    'total_orders' => $totalOrders,
                    'total_amount' => round($totalAmount, $decimalPlaces),
                    'currency' => $baseCurrency?->symbol ?? 'IQD',
                ];
            })
            ->filter(fn ($item) => $item['total_orders'] > 0)
            ->sortByDesc('total_amount')
            ->take(20)
            ->values()
            ->map(function ($item, $index) {
                return array_merge(['rank' => $index + 1], $item);
            });
    }
}
