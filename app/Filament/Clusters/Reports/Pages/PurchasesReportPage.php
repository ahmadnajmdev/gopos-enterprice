<?php

namespace App\Filament\Clusters\Reports\Pages;

use App\Services\Reports\PurchasesReport;

class PurchasesReportPage extends BaseReportPage
{
    protected static ?int $navigationSort = 2;

    protected function getReportClass(): string
    {
        return PurchasesReport::class;
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Purchases Report');
    }
}
