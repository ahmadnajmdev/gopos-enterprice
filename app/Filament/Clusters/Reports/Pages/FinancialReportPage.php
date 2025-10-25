<?php

namespace App\Filament\Clusters\Reports\Pages;

use App\Services\Reports\FinancialReport;

class FinancialReportPage extends BaseReportPage
{
    protected static ?int $navigationSort = 3;

    protected function getReportClass(): string
    {
        return FinancialReport::class;
    }


    public static function getNavigationGroup(): ?string
    {
        return __('Financial Report');
    }
}
