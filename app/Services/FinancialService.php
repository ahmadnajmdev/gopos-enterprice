<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Purchase;
use App\Models\PurchaseReturn;
use App\Models\Sale;
use App\Models\SaleReturn;

class FinancialService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function getIncome($startDate = null, $endDate = null): float|int
    {
        $totalSalesQuery = Sale::query();
        $saleReturnsQuery = SaleReturn::query();
        $incomesQuery = Income::query();

        if ($startDate !== null && $endDate !== null) {
            $totalSalesQuery->whereBetween('sale_date', [$startDate, $endDate]);
            $saleReturnsQuery->whereBetween('sale_return_date', [$startDate, $endDate]);
            $incomesQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $totalSales = $totalSalesQuery->sum('amount_in_base_currency');
        $saleReturns = $saleReturnsQuery->sum('amount_in_base_currency');

        $totalSaleAmount = $totalSales - $saleReturns;

        $incomes = Income::query();
        if ($startDate !== null && $endDate !== null) {
            $incomes->whereBetween('created_at', [$startDate, $endDate]);
        }
        $incomes = $incomesQuery->sum('amount_in_base_currency');

        return $totalSaleAmount + $incomes;
    }

    public static function getExpense($startDate = null, $endDate = null): float|int
    {
        $expensesQuery = Expense::query();
        $purchasesQuery = Purchase::query();
        $purchaseReturnsQuery = PurchaseReturn::query();

        if ($startDate !== null && $endDate !== null) {
            $expensesQuery->whereBetween('created_at', [$startDate, $endDate]);
            $purchasesQuery->whereBetween('created_at', [$startDate, $endDate]);
            $purchaseReturnsQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $expenses = $expensesQuery->sum('amount_in_base_currency');
        $purchases = $purchasesQuery->sum('amount_in_base_currency');
        $purchaseReturns = $purchaseReturnsQuery->sum('amount_in_base_currency');
        $purchases = $purchases - $purchaseReturns;

        return $expenses + $purchases;
    }

    public static function getProfit($startDate = null, $endDate = null): float|int
    {
        $income = self::getIncome($startDate, $endDate);
        $expense = self::getExpense($startDate, $endDate);

        return $income - $expense;
    }
}
