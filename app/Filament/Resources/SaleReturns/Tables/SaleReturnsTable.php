<?php

namespace App\Filament\Resources\SaleReturns\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SaleReturnsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sale_return_number')
                    ->label(__('Return Number'))
                    ->searchable()
                    ->copyable()
                    ->sortable(),
                TextColumn::make('sale.sale_number')
                    ->label(__('Sale Number'))
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('sale.customer.name')
                    ->label(__('Customer'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('sale_return_date')
                    ->label(__('Return Date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('currency.code')
                    ->label(__('Currency'))
                    ->sortable(),
                TextColumn::make('sub_total')
                    ->label(__('Subtotal'))
                    ->numeric()
                    ->sortable()
                    ->money(fn ($record) => $record->currency?->code),
                TextColumn::make('discount')
                    ->numeric()
                    ->sortable()
                    ->money(fn ($record) => $record->currency?->code),
                TextColumn::make('total_amount')
                    ->label(__('Total'))
                    ->numeric()
                    ->sortable()
                    ->money(fn ($record) => $record->currency?->code)
                    ->weight('bold'),
                TextColumn::make('paid_amount')
                    ->label(__('Paid'))
                    ->numeric()
                    ->sortable()
                    ->money(fn ($record) => $record->currency?->code),
                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'Pending',
                        'success' => 'Completed',
                        'danger' => 'Rejected',
                    ]),
                TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('Updated At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
