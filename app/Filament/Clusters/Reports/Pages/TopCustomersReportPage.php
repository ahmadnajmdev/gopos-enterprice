<?php

namespace App\Filament\Clusters\Reports\Pages;

use App\Services\Reports\TopCustomersReport;

class TopCustomersReportPage extends BaseReportPage
{
    protected static ?int $navigationSort = 9;

    protected function getReportClass(): string
    {
        return TopCustomersReport::class;
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Customer Reports');
    }
}

