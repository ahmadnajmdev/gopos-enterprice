<?php

namespace App\Providers;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Facades\FilamentView;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\View\PanelsRenderHook;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        FilamentView::registerRenderHook(
            PanelsRenderHook::SCRIPTS_BEFORE,
            fn (): string => Blade::render('<script>window.JS_MD5_NO_NODE_JS = true;</script>'),
        );
        Fieldset::configureUsing(fn (Fieldset $fieldset) => $fieldset
            ->columnSpanFull());

        Grid::configureUsing(fn (Grid $grid) => $grid
            ->columnSpanFull());

        Section::configureUsing(fn (Section $section) => $section
            ->columnSpanFull());

        FileUpload::configureUsing(fn (FileUpload $fileUpload) => $fileUpload
            ->disk('public')
            ->visibility('public'));

        ImageColumn::configureUsing(fn (ImageColumn $imageColumn) => $imageColumn
            ->visibility('public'));

        ImageEntry::configureUsing(fn (ImageEntry $imageEntry) => $imageEntry
            ->visibility('public'));

        FilamentColor::register([
            'green' => Color::Green,
            'red' => Color::Red,
        ]);

        Model::unguard();
        \BezhanSalleh\LanguageSwitch\LanguageSwitch::configureUsing(function (\BezhanSalleh\LanguageSwitch\LanguageSwitch $switch) {
            $switch
                ->visible(outsidePanels: true)
                ->circular()
                ->labels([
                    'en' => 'English',
                    'ckb' => 'کوردی',
                    'ar' => 'العربیە',
                ])
                ->locales(['en', 'ckb', 'ar']);
        });

        Table::configureUsing(function (Table $table) {
            $table->defaultSort('id', 'desc');
        });

        TextEntry::configureUsing(function (TextEntry $entry) {
            $entry->translateLabel();
        });

        Repeater::configureUsing(function (Repeater $repeater) {
            $repeater->translateLabel();
        });
        DatePicker::configureUsing(function (DatePicker $datePicker) {
            $datePicker->translateLabel();
        });

        ImageEntry::configureUsing(function (ImageEntry $entry) {
            $entry->translateLabel();
        });

        IconEntry::configureUsing(function (IconEntry $entry) {
            $entry->translateLabel();
        });

        Select::configureUsing(function (Select $select) {
            $select
                ->translateLabel()
                ->native(false)
                ->searchable()
                ->preload();
        });

        TextInput::configureUsing(function (TextInput $input) {
            $input->translateLabel();
        });

        TextColumn::configureUsing(function (TextColumn $column) {
            $column->translateLabel();
        });

        Textarea::configureUsing(function (Textarea $textarea) {
            $textarea->translateLabel();
        });

        IconColumn::configureUsing(function (IconColumn $column) {
            $column->translateLabel();
        });

        ImageColumn::configureUsing(function (ImageColumn $column) {
            $column->translateLabel();
        });

        FileUpload::configureUsing(function (FileUpload $fileUpload) {
            $fileUpload->translateLabel();
        });

    }
}
