# Reports Cluster Setup Documentation

## Overview

This document describes the new clustered reports system that uses Filament clusters for organizing report types with a reusable base template.

## Architecture

### Directory Structure

```
app/
├── Filament/
│   └── Clusters/
│       └── Reports/
│           ├── ReportsCluster.php          # Main cluster configuration
│           └── Pages/
│               ├── BaseReportPage.php      # Base class for all reports
│               ├── SalesReportPage.php
│               ├── PurchasesReportPage.php
│               ├── FinancialReportPage.php
│               ├── SaleByProductReportPage.php
│               └── SaleByCategoryReportPage.php
├── Services/
│   └── Reports/
│       ├── BaseReport.php                  # Abstract base report service
│       ├── SalesReport.php
│       ├── PurchasesReport.php
│       ├── FinancialReport.php
│       ├── SaleByProductReport.php
│       └── SaleByCategoryReport.php
└── Console/
    └── Commands/
        └── MakeReportPage.php              # Artisan command for generating reports

resources/
└── views/
    └── filament/
        └── clusters/
            └── reports/
                └── pages/
                    └── base-report.blade.php   # Shared view template
```

## Key Features

### 1. Cluster Navigation
- All reports are organized under a single "Reports" navigation item
- Sub-navigation appears as tabs at the top of each report page
- Consistent navigation group: "Reports & Analytics"

### 2. Base Report Page Class
All report pages extend `BaseReportPage` which provides:
- Common form handling (start date, end date filters)
- PDF download functionality
- Automatic report service integration
- Consistent UI and behavior

### 3. Shared View Template
One Blade template (`base-report.blade.php`) serves all report types:
- Responsive table with dark mode support
- RTL support for Arabic and Kurdish
- Automatic totals row rendering
- Empty state handling
- Consistent styling and icons

### 4. Report Service Pattern
Each report type has a service class that extends `BaseReport`:
- Defines columns structure
- Implements data fetching logic
- Configures totals display
- Specifies report title

## Creating a New Report

### Option 1: Using the Artisan Command (Recommended)

```bash
php artisan make:report-page InventoryReport
```

This command will:
1. Create a new report page class
2. Optionally create the report service if it doesn't exist
3. Automatically assign the next available navigation sort order
4. Place files in the correct directories

**With custom service name:**
```bash
php artisan make:report-page StockReport --service=InventoryReport
```

### Option 2: Manual Creation

#### Step 1: Create Report Service

Create a new file in `app/Services/Reports/YourReport.php`:

```php
<?php

namespace App\Services\Reports;

use Illuminate\Support\Collection;

class YourReport extends BaseReport
{
    protected string $title = 'Your Report Title';
    protected bool $showTotals = true;

    protected array $columns = [
        'column1' => ['label' => 'Column 1', 'type' => 'text'],
        'column2' => ['label' => 'Amount', 'type' => 'currency'],
        'column3' => ['label' => 'Quantity', 'type' => 'number'],
        'column4' => ['label' => 'Date', 'type' => 'date'],
    ];

    protected array $totalColumns = ['column2', 'column3'];

    public function getData(string $startDate, string $endDate): Collection
    {
        // Your data fetching logic here
        return YourModel::query()
            ->whereBetween('date_column', [$startDate, $endDate])
            ->get()
            ->map(function ($item) {
                return [
                    'column1' => $item->name,
                    'column2' => $item->amount,
                    'column3' => $item->quantity,
                    'column4' => $item->date,
                    'currency' => $item->currency?->symbol ?? 'IQD',
                ];
            });
    }
}
```

#### Step 2: Create Report Page

Create a new file in `app/Filament/Clusters/Reports/Pages/YourReportPage.php`:

```php
<?php

namespace App\Filament\Clusters\Reports\Pages;

use App\Services\Reports\YourReport;

class YourReportPage extends BaseReportPage
{
    protected static ?int $navigationSort = 6;

    protected function getReportClass(): string
    {
        return YourReport::class;
    }
}
```

That's it! The report will automatically appear in the Reports cluster.

## Column Types

The base template supports these column types:

- **text**: Plain text display
- **currency**: Formatted number with currency symbol
- **number**: Formatted number with 2 decimal places
- **date**: Date with calendar icon

## Report Service Properties

### Required Properties

- `$title`: Display title for the report
- `$columns`: Array of column definitions with label and type
- `getData()`: Method that returns report data

### Optional Properties

- `$showTotals`: Boolean to show/hide totals row (default: false)
- `$totalColumns`: Array of column keys to include in totals

## Advanced Features

### Custom Filters

To add custom filters, override the `form()` method in your report page:

```php
public function form(Schema $schema): Schema
{
    return $schema
        ->schema([
            Section::make(__('Report Filters'))
                ->schema([
                    DatePicker::make('startDate')
                        ->label(__('Start Date'))
                        ->required()
                        ->live(),
                    DatePicker::make('endDate')
                        ->label(__('End Date'))
                        ->required()
                        ->live(),
                    Select::make('category')
                        ->label(__('Category'))
                        ->options([...])
                        ->live(),
                ])->columns(3),
        ])
        ->statePath('data');
}
```

### Financial Report Style

For reports with subtotals and special formatting (like the Financial Report), return data with special flags:

```php
public function getData(string $startDate, string $endDate): array
{
    return [
        'rows' => [
            [
                'description' => 'Revenue',
                'amount' => 1000,
                'currency' => 'IQD',
                'is_subtotal' => false,
            ],
            [
                'description' => 'Total Revenue',
                'amount' => 1000,
                'currency' => 'IQD',
                'is_subtotal' => true,
            ],
            // ...
        ]
    ];
}
```

Special flags:
- `is_total`: Marks a grand total row (bold, colored)
- `is_subtotal`: Marks a subtotal row (semi-bold)

### PDF Export

PDF export is automatically available via the "Download PDF" button in the header. The PDF uses:
- Custom Rabar font for RTL support
- Same template as the web view (`resources/views/reports/base-report.blade.php`)
- Proper margins and formatting

To customize PDF settings, override `configureMpdf()` in your report page.

## Benefits of This Architecture

1. **No Code Duplication**: One template serves all reports
2. **Easy to Add Reports**: Single command or two small files
3. **Consistent UX**: All reports look and behave the same
4. **Organized Navigation**: Cluster groups related reports
5. **Maintainable**: Changes to base template affect all reports
6. **Type Safety**: Clear separation between data layer (service) and presentation (page)
7. **Flexible**: Easy to override behavior for specific reports

## Migration from Old System

The old `Reports.php` page can be removed once all reports are migrated to the cluster system. Each report type from the old dropdown menu now has its own dedicated page in the cluster.

## Troubleshooting

### Report Not Appearing in Navigation

1. Make sure `discoverClusters()` is called in `AdminPanelProvider`
2. Check that the report page extends `BaseReportPage`
3. Verify the report service class exists
4. Clear the cache: `php artisan filament:clear-cache`

### PDF Not Downloading

1. Check that the Rabar font exists in `public/css/fonts/`
2. Verify write permissions for temp directory
3. Check that the report service returns valid data

### Styling Issues

The template uses Tailwind CSS classes and supports dark mode. To customize:
1. Edit `resources/views/filament/clusters/reports/pages/base-report.blade.php`
2. Changes will apply to all reports

## Example: Creating a Low Stock Report

```bash
# Generate the report files
php artisan make:report-page LowStockReport
```

Then edit `app/Services/Reports/LowStockReport.php`:

```php
<?php

namespace App\Services\Reports;

use App\Models\Product;
use Illuminate\Support\Collection;

class LowStockReport extends BaseReport
{
    protected string $title = 'Low Stock Products';
    protected bool $showTotals = false;

    protected array $columns = [
        'name' => ['label' => 'Product Name', 'type' => 'text'],
        'sku' => ['label' => 'SKU', 'type' => 'text'],
        'current_stock' => ['label' => 'Current Stock', 'type' => 'number'],
        'min_stock' => ['label' => 'Min Stock Level', 'type' => 'number'],
    ];

    public function getData(string $startDate, string $endDate): Collection
    {
        return Product::query()
            ->whereColumn('current_stock', '<=', 'min_stock')
            ->get()
            ->map(function ($product) {
                return [
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'current_stock' => $product->current_stock,
                    'min_stock' => $product->min_stock,
                ];
            });
    }
}
```

The report will now appear in the Reports cluster automatically!
