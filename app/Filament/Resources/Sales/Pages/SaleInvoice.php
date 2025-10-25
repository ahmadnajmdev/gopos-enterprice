<?php

namespace App\Filament\Resources\Sales\Pages;

use App\Filament\Resources\Sales\SaleResource;
use App\Models\Sale;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class SaleInvoice extends Page
{
    protected static string $resource = SaleResource::class;

    protected string $view = 'filament.resources.sale-resource.pages.sale-invoice';

    public $sale;

    protected function getHeaderActions(): array
    {

        return [
            Action::make('print')
                ->label(__('Print'))
                ->icon('heroicon-o-printer')
                ->url(route('print-sale-invoice', [
                    'sale' => $this->sale,
                ])),
            // Action to add payment amount
            Action::make('addPayment')
                ->label(__('Add Payment'))
                ->icon('heroicon-o-banknotes')
                ->color('success')
                ->visible($this->sale->total_amount > $this->sale->paid_amount)
                ->schema([
                    TextInput::make('amount')
                        ->label(__('Paid amount'))
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->maxValue($this->sale->total_amount - $this->sale->paid_amount)
                        ->minValue(0),
                ])
                ->action(function (array $data): void {
                    $this->sale->paid_amount += $data['amount'];
                    $this->sale->save();
                    Notification::make()
                        ->title(__('Payment added successfully'))
                        ->success()
                        ->send();

                })->successRedirectUrl($this->getResource()::getUrl('index')),
        ];
    }

    public function getTitle(): string
    {
        return __('Invoice').' #'.$this->sale->sale_number;
    }

    public function mount($record): void
    {

        $this->sale = Sale::query()->find($record);

    }
}
