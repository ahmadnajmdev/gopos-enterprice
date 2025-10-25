<?php

namespace App\Filament\Clusters\Reports\Pages;

use App\Services\Reports\StockMovementReport;

class StockMovementReportPage extends BaseReportPage
{
    protected static ?int $navigationSort = 7;

    protected function getReportClass(): string
    {
        return StockMovementReport::class;
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Inventory Reports');
    }
}
