<?php

namespace App\Filament\Resources\Inventory;

use App\Filament\Resources\Inventory\Pages\CreateInventoryMovement;
use App\Filament\Resources\Inventory\Pages\EditInventoryMovement;
use App\Filament\Resources\Inventory\Pages\ListInventoryMovements;
use App\Models\InventoryMovement;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InventoryMovementResource extends Resource
{
    protected static ?string $model = InventoryMovement::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?int $navigationSort = 7;

    public static function getNavigationGroup(): ?string
    {
        return __('Inventory');
    }

    public static function getLabel(): ?string
    {
        return __('Inventory Movement');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Inventory Movements');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('Movement Details'))
                ->schema([
                    Select::make('product_id')
                        ->label(__('Product'))
                        ->relationship('product', 'name')
                        ->required(),
                    Select::make('type')
                        ->label(__('Type'))
                        ->options([
                            'damaged' => __('Damaged'),
                            'destroyed' => __('Destroyed'),
                            'return' => __('Return'),
                            'transfer' => __('Transfer'),
                            'adjustment' => __('Adjustment'),
                        ])
                        ->required(),
                    TextInput::make('quantity')
                        ->numeric()
                        ->required(),
                    Textarea::make('reason')
                        ->nullable(),
                    DateTimePicker::make('movement_date')
                        ->default(now())
                        ->nullable(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')->label(__('Product'))->sortable()->searchable(),
                TextColumn::make('type')->badge()->sortable(),
                TextColumn::make('quantity')->numeric(locale: 'en')->sortable(),
                TextColumn::make('purchase.purchase_number')->label(__('Purchase')),
                TextColumn::make('sale.sale_number')->label(__('Sale')),
                TextColumn::make('user.name')->label(__('User'))->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('movement_date')->dateTime()->sortable(),
                TextColumn::make('created_at')->dateTime()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'purchase' => __('Purchase'),
                        'sale' => __('Sale'),
                        'damaged' => __('Damaged'),
                        'destroyed' => __('Destroyed'),
                        'return' => __('Return'),
                        'transfer' => __('Transfer'),
                        'adjustment' => __('Adjustment'),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInventoryMovements::route('/'),
            'create' => CreateInventoryMovement::route('/create'),
            'edit' => EditInventoryMovement::route('/{record}/edit'),
        ];
    }
}
