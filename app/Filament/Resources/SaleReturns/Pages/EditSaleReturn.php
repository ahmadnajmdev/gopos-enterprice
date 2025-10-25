<?php

namespace App\Filament\Resources\SaleReturns\Pages;

use App\Filament\Resources\SaleReturns\SaleReturnResource;
use App\Models\InventoryMovement;
use App\Models\Payment;
use App\Models\SaleItem;
use App\Models\SaleReturnItem;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSaleReturn extends EditRecord
{
    protected static string $resource = SaleReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            Action::make('complete')
                ->label(__('Complete'))
                ->color('success')
                ->visible(fn (): bool => $this->record->status === 'Pending')
                ->requiresConfirmation()
                ->action(function (): void {
                    $return = $this->record;
                    // Prevent over-return: ensure each line does not exceed sold qty minus previous completed returns
                    foreach ($return->items as $item) {
                        $sold = (float) (SaleItem::query()
                            ->where('sale_id', $return->sale_id)
                            ->where('product_id', $item->product_id)
                            ->value('stock') ?? 0);

                        $previousReturned = (float) (SaleReturnItem::query()
                            ->where('product_id', $item->product_id)
                            ->whereHas('saleReturn', function ($q) use ($return) {
                                $q->where('sale_id', $return->sale_id)->where('status', 'Completed');
                            })
                            ->sum('return_stock'));

                        $available = max(0, $sold - $previousReturned);
                        if ($item->return_stock > $available) {
                            $this->addError('items', __('Return quantity exceeds available for some items.'));

                            return;
                        }
                    }
                    foreach ($return->items as $item) {
                        InventoryMovement::create([
                            'product_id' => $item->product_id,
                            'type' => 'sale_return',
                            'quantity' => (int) $item->return_stock,
                            'sale_id' => $return->sale_id,
                            'user_id' => auth()->id(),
                            'reason' => 'Sale return completed',
                            'movement_date' => now(),
                        ]);
                    }

                    $return->update(['status' => 'Completed']);

                    Payment::create([
                        'reference_id' => $return->id,
                        'type' => 'return',
                        'amount' => $return->total_amount,
                        'currency_id' => $return->currency_id,
                        'exchange_rate' => $return->exchange_rate,
                        'amount_in_base_currency' => $return->amount_in_base_currency,
                        'note' => 'Auto refund on sale return completion',
                    ]);
                }),
            Action::make('reject')
                ->label(__('Reject'))
                ->color('danger')
                ->visible(fn (): bool => $this->record->status === 'Pending')
                ->requiresConfirmation()
                ->action(function (): void {
                    $this->record->update(['status' => 'Rejected']);
                }),
            DeleteAction::make(),
        ];
    }
}
