<?php

namespace App\Services\Reports;

use App\Models\InventoryMovement;
use Illuminate\Support\Collection;

class StockMovementReport extends BaseReport
{
    protected string $title = 'Stock Movement Report';

    protected bool $showTotals = false;

    protected array $columns = [
        'date' => ['label' => 'Date', 'type' => 'date'],
        'product' => ['label' => 'Product', 'type' => 'text'],
        'type' => ['label' => 'Type', 'type' => 'text'],
        'quantity' => ['label' => 'Quantity', 'type' => 'text'],
        'reference' => ['label' => 'Reference', 'type' => 'text'],
        'notes' => ['label' => 'Notes', 'type' => 'text'],
    ];

    public function getData(string $startDate, string $endDate): Collection
    {
        return InventoryMovement::query()
            ->with(['product', 'product.unit'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($movement) {
                $unitAbbr = $movement->product?->unit?->abbreviation ?? '';
                $quantity = number_format($movement->quantity, 2) . ($unitAbbr ? ' ' . $unitAbbr : '');

                return [
                    'date' => $movement->created_at->format('Y-m-d H:i'),
                    'product' => $movement->product?->name ?? 'N/A',
                    'type' => $this->formatMovementType($movement->type),
                    'quantity' => $quantity,
                    'reference' => $movement->reference_type ? class_basename($movement->reference_type) . ' #' . $movement->reference_id : 'N/A',
                    'notes' => $movement->notes ?? '-',
                ];
            });
    }

    private function formatMovementType(?string $type): string
    {
        return match ($type) {
            'in' => 'Stock In',
            'out' => 'Stock Out',
            'adjustment' => 'Adjustment',
            default => $type ?? 'N/A'
        };
    }
}
