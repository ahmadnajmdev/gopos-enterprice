<?php

namespace App\Filament\Resources\Incomes;

use App\Filament\Resources\Incomes\Pages\ManageIncomes;
use App\Filament\Resources\IncomeTypes\IncomeTypeResource;
use App\Models\Currency;
use App\Models\Income;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;

class IncomeResource extends Resource
{
    protected static ?string $model = Income::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-trending-up';

    protected static ?int $navigationSort = 9;

    public static function getNavigationGroup(): ?string
    {
        return __('Accounting');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('currency_id')
                    ->relationship('currency', 'name')
                    ->live()
                    ->default(fn (callable $get) => Currency::getBaseCurrency()?->id)
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                        $oldCurrency = Currency::find($old);
                        $currency = Currency::find($state);
                        $oldAmount = $get('amount');
                        if ($oldCurrency) {
                            $newAmount = $oldAmount ? Currency::convertToCurrency($oldAmount, $currency?->code, $oldCurrency->code) : null;
                        } else {
                            $newAmount = $oldAmount ? Currency::convertToCurrency($oldAmount, $currency?->code, Currency::getBaseCurrency()?->code) : null;
                        }
                        $set('amount', $newAmount);
                        $set('exchange_rate', $currency ? $currency->exchange_rate : (Currency::getBaseCurrency()?->exchange_rate ?? 1));
                    })
                    ->required(),
                Hidden::make('exchange_rate')
                    ->default(fn (callable $get) => $get('currency')?->exchange_rate ?? Currency::getBaseCurrency()?->exchange_rate ?? 1),
                Select::make('income_type_id')
                    ->relationship('type', 'name')
                    ->required()
                    ->createOptionForm(fn (Schema $schema) => IncomeTypeResource::form($schema)),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->suffix(function (Get $get) {
                        $currency = Currency::find($get('currency_id'));

                        return $currency ? $currency->symbol : (Currency::getBaseCurrency()?->symbol);
                    }),
                Textarea::make('description')
                    ->columnSpanFull(),
                Textarea::make('note')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('amount')
                    ->numeric(locale: 'en')
                    ->suffix(fn ($record) => ' '.$record->currency ? $record->currency->symbol : (Currency::getBaseCurrency()?->symbol))
                    ->sortable(),
                TextColumn::make('type.name')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                QueryBuilder::make()
                    ->constraints([
                        RelationshipConstraint::make('type')
                            ->label(__('Income Type'))
                            ->relationship('type', 'name'),
                        NumberConstraint::make('amount')
                            ->label(__('Amount')),
                        TextConstraint::make('note')
                            ->label(__('Note')),
                        DateConstraint::make('created_at')
                            ->label(__('Created at')),
                    ]),
            ])
            ->filtersLayout(FiltersLayout::AboveContentCollapsible)
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getLabel(): string
    {
        return __('Income');
    }

    public static function getPluralLabel(): string
    {
        return __('Incomes');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageIncomes::route('/'),
        ];
    }
}
