<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use App\Services\FinancialService;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\HtmlString;

class Dashboard extends BaseWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 1;

    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $startDate = $this->pageFilters['startDate'] ?? null;
        $endDate = $this->pageFilters['endDate'] ?? null;
        $sales = Sale::query()->count();
        $customers = Customer::query()->count();
        $suppliers = Supplier::query()->count();
        $products = Product::query()->count();
        $purchases = Purchase::query()->count();

        $income = FinancialService::getIncome($startDate, $endDate);
        $expenses = FinancialService::getExpense($startDate, $endDate);
        $profit = FinancialService::getProfit($startDate, $endDate);

        $profitPercentage = $income > 0 ? ($profit / $income) * 100 : 0;
        $baseCurrency = \App\Models\Currency::getBaseCurrency();
        $currencySymbol = $baseCurrency?->symbol ?? $baseCurrency?->code ?? __('IQD');
        $currencyDecimals = $baseCurrency?->decimal_places ?? 0;

        $profit = number_format($profit, $currencyDecimals).' '.$currencySymbol;
        $income = number_format($income, $currencyDecimals).' '.$currencySymbol;
        $expenses = number_format($expenses, $currencyDecimals).' '.$currencySymbol;
        $profitPercentage = number_format($profitPercentage, 2).'%';

        $profitLossColor = $profit > 0 ? 'green-500' : 'red-600';

        return [
            Stat::make(__('Sales'), $sales)
                ->icon('heroicon-o-shopping-cart')
                ->color('success')
                ->description(__('Total Sales')),
            Stat::make(__('Customers'), $customers)
                ->icon('heroicon-o-users')
                ->color('primary')
                ->description(__('Total Customers')),
            Stat::make(__('Suppliers'), $suppliers)
                ->icon('heroicon-o-users')
                ->color('primary')
                ->extraAttributes(['class' => 'fi-wi-stats-overview-stat-value text-primary-500'])
                ->description(__('Total Suppliers')),
            Stat::make(__('Products'), $products)
                ->icon('heroicon-o-cube')
                ->color('primary')
                ->description(__('Total Products')),
            Stat::make(__('Purchases'), $purchases)
                ->icon('heroicon-o-shopping-bag')
                ->color('primary')
                ->description(__('Total Purchases')),
            Stat::make(__('Incomes'), $income)
                ->icon('heroicon-o-currency-dollar')
                ->value(new HtmlString('<span class="text-green-500">'.$income.'</span>'))
                ->color('success')
                ->description(__('Total Income')),
            Stat::make(__('Expenses'), $expenses)
                ->icon('heroicon-o-currency-dollar')
                ->value(new HtmlString('<span class="text-danger-600">'.$expenses.'</span>'))
                ->color('danger')
                ->description(__('Total Expenses')),
            Stat::make(__('Profit/Loss'), $profit)
                ->icon('heroicon-o-currency-dollar')
                ->value(new HtmlString('<span class="text-'.$profitLossColor.'">'.$profit.'</span>'))
                ->color('success')
                ->description(__('Net Profit/Loss')),
            Stat::make(__('Profit Percentage'), $profitPercentage)
                ->icon('heroicon-o-currency-dollar')
                ->value(new HtmlString('<span class="text-'.$profitLossColor.'">'.$profitPercentage.'</span>'))
                ->color('success')
                ->description(__('Profit Percentage')),
        ];
    }
}
