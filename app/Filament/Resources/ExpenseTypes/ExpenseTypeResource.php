<?php

namespace App\Filament\Resources\ExpenseTypes;

use App\Filament\Resources\ExpenseTypes\Pages\ManageExpenseTypes;
use App\Models\ExpenseType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ExpenseTypeResource extends Resource
{
    protected static ?string $model = ExpenseType::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static ?int $navigationSort = 12;

    public static function getNavigationGroup(): ?string
    {
        return __('Accounting');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
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
                //
            ])
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
        return __('Expense Type');
    }

    public static function getPluralLabel(): string
    {
        return __('Expense Types');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageExpenseTypes::route('/'),
        ];
    }
}
