<?php

namespace App\Filament\Resources\SaleReturns\Pages;

use App\Filament\Resources\SaleReturns\SaleReturnResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSaleReturn extends ViewRecord
{
    protected static string $resource = SaleReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
