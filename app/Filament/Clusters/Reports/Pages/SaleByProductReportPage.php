<?php

namespace App\Filament\Clusters\Reports\Pages;

use App\Services\Reports\SaleByProductReport;

class SaleByProductReportPage extends BaseReportPage
{
    protected static ?int $navigationSort = 4;

    protected function getReportClass(): string
    {
        return SaleByProductReport::class;
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Sales Report');
    }
}
