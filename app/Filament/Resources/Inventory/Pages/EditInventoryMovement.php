<?php

namespace App\Filament\Resources\Inventory\Pages;

use App\Filament\Resources\Inventory\InventoryMovementResource;
use Filament\Resources\Pages\EditRecord;

class EditInventoryMovement extends EditRecord
{
    protected static string $resource = InventoryMovementResource::class;
}
