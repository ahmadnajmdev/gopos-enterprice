<?php

namespace App\Filament\Clusters\Reports\Pages;

use App\Services\Reports\InventoryValuationReport;

class InventoryValuationReportPage extends BaseReportPage
{
    protected static ?int $navigationSort = 6;

    protected function getReportClass(): string
    {
        return InventoryValuationReport::class;
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Inventory Reports');
    }
}
