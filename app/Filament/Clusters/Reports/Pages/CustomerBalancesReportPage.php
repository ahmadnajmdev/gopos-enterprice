<?php

namespace App\Filament\Clusters\Reports\Pages;

use App\Services\Reports\CustomerBalancesReport;

class CustomerBalancesReportPage extends BaseReportPage
{
    protected static ?int $navigationSort = 8;

    protected function getReportClass(): string
    {
        return CustomerBalancesReport::class;
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Customer Reports');
    }
}
