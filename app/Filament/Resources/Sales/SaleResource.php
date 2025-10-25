<?php

namespace App\Filament\Resources\Sales;

use App\Filament\Resources\Customers\CustomerResource;
use App\Filament\Resources\SaleReturns\SaleReturnResource;
use App\Filament\Resources\Sales\Pages\CreateSale;
use App\Filament\Resources\Sales\Pages\EditSale;
use App\Filament\Resources\Sales\Pages\ListSales;
use App\Filament\Resources\Sales\Pages\SaleInvoice;
use App\Filament\Resources\Sales\Pages\ViewSale;
use App\Models\Product;
use App\Models\Sale;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
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

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return __('Sales');
    }

    private static function calculateProductTotal(Set $set, Get $get): void
    {
        $product = Product::query()->find($get('product_id'));
        $price = $product->price ?? 0.00;
        $stock = $get('stock') ?? 0.00;
        $set('price', $price);
        $set('total_amount', (float) $stock * (float) $price);
        $products = $get('../../products') ?? [];
        $sub_total = 0.00;

        foreach ($products as $index => $product) {
            $sub_total += $product['total_amount'];
        }
        $set('../../sub_total', $sub_total);
        $set('../../total_amount', $sub_total);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Sale Details'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('sale_number')
                            ->required()
                            ->default(fn () => Sale::generateSaleNumber())
                            ->readOnly()
                            ->maxLength(255),
                        Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->placeholder(__('Walk-in Customer'))
                            ->createOptionForm(fn (Schema $schema) => CustomerResource::form($schema)),
                        DatePicker::make('sale_date')
                            ->default(now())
                            ->required(),

                    ])->columns(3)
                    ->columnSpanFull(),
                Section::make(__('Products'))
                    ->schema([
                        Repeater::make('products')
                            ->relationship('items')
                            ->live()
                            ->defaultItems(0)
                            ->table([
                                TableColumn::make(__('Product')),
                                TableColumn::make(__('Stock')),
                                TableColumn::make(__('Price')),
                                TableColumn::make(__('Total amount')),
                            ])
                            ->compact()
                            ->afterStateUpdated(fn (Set $set, Get $get) => self::calculateProductTotal($set, $get))
                            ->addActionLabel(__('Add Product'))
                            ->schema([
                                Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->reactive()
                                    ->afterStateUpdated(fn (Set $set, Get $get) => self::calculateProductTotal($set, $get))
                                    ->required(),
                                TextInput::make('stock')
                                    ->suffix(function (Get $get) {
                                        return Product::query()->find($get('product_id'))->unit?->abbreviation ?? '';
                                    })
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, Get $get) => self::calculateProductTotal($set, $get))
                                    ->required(),
                                TextInput::make('price')
                                    ->required()
                                    ->readOnly()
                                    ->suffix(' '.__('IQD'))
                                    ->default(0.00),
                                TextInput::make('total_amount')
                                    ->required()
                                    ->suffix(' '.__('IQD'))
                                    ->readOnly(),
                            ])
                            ->columns(4),
                    ])
                    ->columnSpanFull(),
                Section::make(__('Purchase Payment'))
                    ->schema([
                        TextInput::make('sub_total')
                            ->required()
                            ->readOnly(),
                        TextInput::make('discount_amount')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                $set('total_amount', (float) $get('sub_total') - (float) $get('discount_amount'));
                            })
                            ->maxValue(fn (Get $get) => $get('sub_total'))
                            ->default(0.00),
                        TextInput::make('total_amount')
                            ->required()
                            ->readOnly(),
                        TextInput::make('paid_amount')
                            ->required()
                            ->numeric()
                            ->maxValue(fn (Get $get) => $get('total_amount'))
                            ->default(0.00),
                    ])
                    ->columns(4)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->placeholder(__('Walk-in Customer'))
                    ->sortable(),
                TextColumn::make('sub_total')
                    ->numeric(locale: 'en')
                    ->suffix(' '.__('IQD'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('discount')
                    ->numeric(locale: 'en')
                    ->suffix(' '.__('IQD'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->numeric(locale: 'en')
                    ->suffix(' '.__('IQD'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('paid_amount')
                    ->numeric(locale: 'en')
                    ->suffix(' '.__('IQD'))
                    ->searchable()
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
                        TextConstraint::make('sale_number')
                            ->label(__('Sale number')),
                        RelationshipConstraint::make('customer')
                            ->label(__('Customer'))
                            ->relationship('customer', 'name'),
                        NumberConstraint::make('total_amount')
                            ->label(__('Amount')),
                        NumberConstraint::make('paid_amount')
                            ->label(__('Paid amount')),
                        DateConstraint::make('sale_date')
                            ->label(__('Sale date')),
                    ]),
            ])
            ->filtersLayout(FiltersLayout::AboveContentCollapsible)
            ->recordActions([
                Action::make('view_invoice')
                    ->label(__('View Invoice'))
                    ->icon('heroicon-o-document')
                    ->color('success')
                    ->url(function ($record) {
                        return self::getUrl('invoice', ['record' => $record]);
                    }),
                Action::make('create_return')
                    ->label(__('Return'))
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('warning')
                    ->url(fn ($record) => SaleReturnResource::getUrl('create', parameters: [
                        'sale_id' => $record->id,
                    ])),
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getLabel(): string
    {
        return __('Sale');
    }

    public static function getPluralLabel(): string
    {
        return __('Sales');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSales::route('/'),
            'create' => CreateSale::route('/create'),
            'edit' => EditSale::route('/{record}/edit'),
            'view' => ViewSale::route('/{record}'),
            'invoice' => SaleInvoice::route('/{record}/invoice'),
        ];
    }
}
