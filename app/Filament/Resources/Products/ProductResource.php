<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Filament\Resources\Products\Pages\ViewProduct;
use App\Filament\Resources\Units\UnitResource;
use App\Models\Currency;
use App\Models\Product;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cube';

    protected static ?int $navigationSort = 5;

    public static function getNavigationGroup(): ?string
    {
        return __('Inventory');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Product Information'))
                    ->schema([
                        FileUpload::make('image')
                            ->maxSize(4096)
                            ->image(),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('barcode')
                            ->label(__('Barcode'))
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->createOptionForm(fn (Schema $schema) => CategoryResource::form($schema))
                            ->required(),

                        Textarea::make('description')
                            ->columnSpanFull(),
                    ])
                    ->columnSpan(2)
                    ->columns(3),

                Section::make(__('Stock & Pricing'))
                    ->schema([
                        Select::make('unit_id')
                            ->relationship('unit', 'name')
                            ->createOptionForm(fn (Schema $schema) => UnitResource::form($schema))
                            ->required(),
                        Hidden::make('stock')
                            ->default(0)
                            ->required(),
                        TextInput::make('low_stock_alert')
                            ->numeric()
                            ->label(__('Low stock alert'))
                            ->helperText(__('Show alert when stock is below this number')),
                        TextInput::make('cost')
                            ->required()
                            ->numeric()
                            ->prefix(Currency::getBaseCurrency()->symbol),
                        TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix(Currency::getBaseCurrency()->symbol),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->square(),
                TextColumn::make('category.name')
                    ->numeric(locale: 'en')
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('barcode')
                    ->label(__('Barcode'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('price')
                    ->numeric(locale: 'en')
                    ->suffix(' '.Currency::getBaseCurrency()->symbol)
                    ->sortable(),
                TextColumn::make('stock')
                    ->numeric(locale: 'en')
                    ->suffix(function ($record) {
                        return $record->stock > 0 ? ' '.$record->unit?->abbreviation : '';
                    })
                    ->sortable(),
                TextColumn::make('low_stock_alert')
                    ->label(__('Low alert'))
                    ->toggleable(isToggledHiddenByDefault: true),
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
                        TextConstraint::make('name')
                            ->label(__('Name')),
                        RelationshipConstraint::make('category')
                            ->label(__('Category'))
                            ->relationship('category', 'name'),
                        NumberConstraint::make('price')
                            ->label(__('Price')),
                        NumberConstraint::make('stock')
                            ->label(__('Stock')),
                        DateConstraint::make('created_at')
                            ->label(__('Created at')),
                    ]),
            ])
            ->filtersLayout(FiltersLayout::AboveContentCollapsible)
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('mark_damaged')
                        ->label(__('Mark as Damaged'))
                        ->icon('heroicon-o-exclamation-triangle')
                        ->form([
                            TextInput::make('quantity')
                                ->numeric()
                                ->required(),
                            Textarea::make('reason')->label(__('Reason'))->nullable(),
                        ])
                        ->action(function (array $data, $records) {
                            foreach ($records as $product) {
                                \App\Models\InventoryMovement::create([
                                    'product_id' => $product->id,
                                    'type' => 'damaged',
                                    'quantity' => -(int) $data['quantity'],
                                    'user_id' => auth()->id(),
                                    'reason' => $data['reason'] ?? null,
                                    'movement_date' => now(),
                                ]);
                            }
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\Products\RelationManagers\MovementsRelationManager::class,
        ];
    }

    public static function getLabel(): string
    {
        return __('Product');
    }

    public static function getPluralLabel(): string
    {
        return __('Products');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'view' => ViewProduct::route('/{record}'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }
}
