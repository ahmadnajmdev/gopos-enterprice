<?php

namespace App\Services\Reports;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Purchase;
use App\Models\Sale;

class FinancialReport extends BaseReport
{
    protected string $title = 'Financial Report';

    protected bool $showTotals = true;

    protected array $columns = [
        'category' => ['label' => 'Category', 'type' => 'text'],
        'amount' => ['label' => 'Amount', 'type' => 'currency'],
    ];

    public function getData(string $startDate, string $endDate): array
    {
        $sales = Sale::query()
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->sum('amount_in_base_currency');

        $purchases = Purchase::query()
            ->whereBetween('purchase_date', [$startDate, $endDate])
            ->sum('amount_in_base_currency');

        $expenses = Expense::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount_in_base_currency');

        $incomes = Income::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount_in_base_currency');

        $baseCurrency = \App\Models\Currency::getBaseCurrency();
        $currency = $baseCurrency?->symbol ?? $baseCurrency?->code ?? __('IQD');

        return [
            'rows' => [
                ['category' => __('Sales Revenue'), 'amount' => $sales, 'currency' => $currency],
                ['category' => __('Other Income'), 'amount' => $incomes, 'currency' => $currency],
                ['category' => __('Total Revenue'), 'amount' => $sales + $incomes, 'currency' => $currency, 'is_subtotal' => true],
                ['category' => __('Purchases'), 'amount' => $purchases, 'currency' => $currency],
                ['category' => __('Other Expenses'), 'amount' => $expenses, 'currency' => $currency],
                ['category' => __('Total Expenses'), 'amount' => $purchases + $expenses, 'currency' => $currency, 'is_subtotal' => true],
                ['category' => __('Net Profit/Loss'), 'amount' => ($sales + $incomes) - ($purchases + $expenses), 'currency' => $currency, 'is_total' => true],
            ],
        ];
    }
}
