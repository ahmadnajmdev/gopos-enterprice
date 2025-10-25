<?php

namespace App\Filament\Resources\Customers;

use App\Filament\Resources\Customers\Pages\CreateCustomer;
use App\Filament\Resources\Customers\Pages\CustomerStatement;
use App\Filament\Resources\Customers\Pages\EditCustomer;
use App\Filament\Resources\Customers\Pages\ListCustomers;
use App\Filament\Resources\Customers\Pages\ViewCustomer;
use App\Models\Currency;
use App\Models\Customer;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\BooleanConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';

    protected static ?int $navigationSort = 4;

    public static function getNavigationGroup(): ?string
    {
        return __('Sales');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Customer Information'))
                    ->columns(2)
                    ->schema([
                        FileUpload::make('image')
                            ->avatar()
                            ->image()
                            ->columnSpanFull(),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->maxLength(255)
                            ->default(null),
                        TextInput::make('phone')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('address'),
                        Textarea::make('note')
                            ->columnSpanFull(),
                        Toggle::make('active')
                            ->default(true)
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->circular(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('balance')
                    ->label(__('Balance'))
                    ->numeric(locale: 'en')
                    ->suffix(' '.Currency::getBaseCurrency()->symbol)
                    ->getStateUsing(function ($record): float {
                        $totalSales = $record->sales()->sum('amount_in_base_currency');
                        $totalPaid = $record->sales->sum(function ($sale) {
                            if ($sale->currency_id == Currency::getBaseCurrency()->id) {
                                return $sale->paid_amount;
                            }

                            return $sale->currency->convertFromCurrency($sale->paid_amount, $sale->currency->code);
                        });

                        return round($totalSales - $totalPaid, Currency::getBaseCurrency()->decimal_places ?? 2);
                    })
                    ->color(function ($record): string {
                        $totalSales = $record->sales()->sum('amount_in_base_currency');
                        $totalPaid = $record->sales->sum(function ($sale) {
                            if ($sale->currency_id == Currency::getBaseCurrency()->id) {
                                return $sale->paid_amount;
                            }

                            return $sale->currency->convertFromCurrency($sale->paid_amount, $sale->currency->code);
                        });

                        return ($totalSales - $totalPaid) > 0 ? 'danger' : 'success';
                    })

                    ->sortable(),
                IconColumn::make('active')
                    ->boolean(),
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
                        TextConstraint::make('email')
                            ->label(__('Email')),
                        TextConstraint::make('phone')
                            ->label(__('Phone')),
                        BooleanConstraint::make('active')
                            ->label(__('Active')),
                        DateConstraint::make('created_at')
                            ->label(__('Created at')),
                    ]),
            ])
            ->filtersLayout(FiltersLayout::AboveContentCollapsible)
            ->recordActions([
                Action::make('statement')
                    ->label(__('Statement'))
                    ->icon('heroicon-o-document-text')
                    ->color('success')
                    ->url(function ($record) {
                        return self::getUrl('statement', ['record' => $record]);
                    }),
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Customer Information'))
                    ->schema([
                        ImageEntry::make('image')
                            ->columnSpanFull()
                            ->circular(),
                        TextEntry::make('name'),
                        TextEntry::make('email'),
                        TextEntry::make('phone'),
                        TextEntry::make('address'),
                        TextEntry::make('note'),
                        IconEntry::make('active')
                            ->boolean(),
                    ])->columns(2),
            ]);
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
            'index' => ListCustomers::route('/'),
            'create' => CreateCustomer::route('/create'),
            'view' => ViewCustomer::route('/{record}'),
            'edit' => EditCustomer::route('/{record}/edit'),
            'statement' => CustomerStatement::route('/{record}/statement'),
        ];
    }

    public static function getLabel(): string
    {
        return __('Customer');
    }

    public static function getPluralLabel(): string
    {
        return __('Customers');
    }
}
