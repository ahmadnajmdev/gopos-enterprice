<?php

namespace App\Filament\Resources\Inventory\Pages;

use App\Filament\Resources\Inventory\InventoryMovementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInventoryMovements extends ListRecords
{
    protected static string $resource = InventoryMovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
