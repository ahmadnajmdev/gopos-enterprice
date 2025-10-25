<?php

namespace App\Filament\Resources\Sales\Pages;

use App\Filament\Resources\Sales\SaleResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListSales extends ListRecords
{
    protected static string $resource = SaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('POS')
                ->url('/pos')
                ->icon('heroicon-o-shopping-cart')
                ->color('success')
                ->label(__('POS')),
        ];
    }
}
