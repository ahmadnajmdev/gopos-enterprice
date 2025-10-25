<?php

namespace App\Filament\Clusters\Reports;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;

class ReportsCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentChartBar;

    protected static ?int $navigationSort = 13;

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Start;

    public static function getNavigationLabel(): string
    {
        return __('Reports');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Reports & Analytics');
    }

    public static function getClusterBreadcrumb(): string
    {
        return __('Reports');
    }
}
