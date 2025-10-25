<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DashboardPage extends BaseDashboard
{
    use HasFiltersForm;

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Filters'))
                    ->schema([
                        DatePicker::make('startDate')->label(__('Start Date')),
                        DatePicker::make('endDate')->label(__('End Date')),
                    ])
                    ->columns(2),
            ]);
    }
}
