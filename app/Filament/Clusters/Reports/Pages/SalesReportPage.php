<?php

namespace App\Filament\Clusters\Reports\Pages;

use App\Services\Reports\SalesReport;

class SalesReportPage extends BaseReportPage
{
    protected static ?int $navigationSort = 1;

    protected function getReportClass(): string
    {
        return SalesReport::class;
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Sales Report');
    }
}
