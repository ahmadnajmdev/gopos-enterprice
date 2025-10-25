<?php

namespace App\Filament\Resources\Sales\Pages;

use App\Filament\Resources\Sales\SaleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSale extends ViewRecord
{
    protected static string $resource = SaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('view_invoice')
                ->label(__('View Invoice'))
                ->color('success')
                ->url(fn () => route('filament.admin.resources.sales.invoice', ['record' => $this->record->getKey()]))
                ->icon('heroicon-o-document-text'),
        ];
    }
}
