<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    public static function boot()
    {
        parent::boot();

        static::creating(function ($purchase) {
            $purchase->purchase_number = Purchase::generatePurchaseNumber();

            if ($purchase->exchange_rate === null && $purchase->currency) {
                $purchase->exchange_rate = $purchase->currency->exchange_rate;
            }

            // Set amount_in_base_currency
            if ($purchase->total_amount !== null && $purchase->currency) {
                $purchase->amount_in_base_currency = $purchase->currency->convertFromCurrency($purchase->total_amount, $purchase->currency->code);
            }
        });

        // Stock movements are handled by PurchaseItem events

        // Updates are handled by PurchaseItem events
        // Deletes handled by PurchaseItem events
    }

    public function getAmountDueAttribute(): float
    {
        return $this->total_amount - $this->paid_amount;
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function getAmountInBaseCurrencyAttribute()
    {
        return $this->currency?->convertFromCurrency($this->total_amount, $this->currency->code);
    }

    public function returns(): HasMany
    {
        return $this->hasMany(PurchaseReturn::class);
    }

    public static function generatePurchaseNumber(): string
    {
        $lastPurchase = Purchase::latest()->first();

        return $lastPurchase ? 'PUR-'.str_pad($lastPurchase->id + 1, 5, '0', STR_PAD_LEFT) : 'PUR-00001';
    }
}
