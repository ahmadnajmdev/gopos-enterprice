<?php

namespace App\Filament\Resources\Expenses;

use App\Filament\Resources\Expenses\Pages\ManageExpenses;
use App\Filament\Resources\ExpenseTypes\ExpenseTypeResource;
use App\Models\Expense;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-trending-down';

    protected static ?int $navigationSort = 11;

    public static function getNavigationGroup(): ?string
    {
        return __('Accounting');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('expense_type_id')
                    ->relationship('type', 'name')
                    ->createOptionForm(fn (Schema $schema) => ExpenseTypeResource::form($schema))
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Textarea::make('note')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('amount')

                    ->numeric(locale: 'en')
                    ->suffix(' '.__('IQD'))
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
                            ->label(__('Expense Type'))
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
        return __('Expense');
    }

    public static function getPluralLabel(): string
    {
        return __('Expenses');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageExpenses::route('/'),
        ];
    }
}
